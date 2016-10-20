<?php

namespace App\Console\Commands;

use App\Events\Backend\TmplOrderPaymentError;
use App\Exceptions\AutomaticallyPayException;
use App\Exceptions\UnsupportedPaymentMethod;
use App\Models\Card;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Exception;

/**
 * Class ProcessTmplOrdersForCurrentWeek
 * @package App\Console\Commands
 */
class ProcessTmplOrdersForCurrentWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-tmp-orders-for-current-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process tmpl orders with delivery for the current week';
    
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * Create a new command instance.
     *
     * @param \App\Services\PaymentService $paymentService
     * @param \App\Services\OrderService   $orderService
     */
    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
        
        $this->orderService = $orderService;
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->log('Start '.$this->description);
        
        foreach (Order::ofStatus('tmpl')->forCurrentWeek()->get() as $order) {
            try {
                $this->orderService->updatePrices($order);

                list($subtotal, $total) = $this->orderService->getTotals($order);

                $order->subtotal = $subtotal;
                $order->total = $total;

                $order->save();
                
                $this->log('process order #'.$order->id, 'info', $order->toArray());
                
                $card = Card::ofUser($order->user_id)->default()->first();
                
                if ($card) {
                    if ($this->paymentService->automaticallyPay($order, $card)) {
                        continue;
                    }
                    
                    $message = 'Order in processed status';
                } else {
                    $message = trans('messages.no selected default cards admin message');
                    
                    $this->_sendUserMessage($order, trans('messages.no selected default cards user message'));
                    $this->_sendAdminMessage($order, $message);
                    
                    $this->_setChangedStatus($order, $message);
                }
            } catch (UnsupportedPaymentMethod $e) {
                $message = $e->getMessage();
                
                $this->_sendUserMessage($order, $message);
                $this->_sendAdminMessage($order, $message);
                
                $this->_setChangedStatus($order, $message);
            } catch (AutomaticallyPayException $e) {
                $message = $e->getMessage();
                
                $this->_sendAdminMessage($order, $message);
                
                $this->_setChangedStatus($order, $message);
            } catch (Exception $e) {
                $message = 'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
                
                $this->_sendAdminMessage($order, $message);
            }
            
            $this->log($message, 'error');
            
            $this->log('end process order #'.$order->id, 'info');
        }
        
        $this->log('End '.$this->description);
    }
    
    /**
     * @param \App\Models\Order $order
     * @param string            $message
     */
    private function _sendUserMessage(Order $order, $message)
    {
        event(new TmplOrderPaymentError($order, $message));
    }
    
    /**
     * @param \App\Models\Order $order
     * @param string            $message
     */
    private function _sendAdminMessage(Order $order, $message)
    {
        admin_notify($this->description.' error: '.$message, $order->toArray());
    }
    
    /**
     * @param \App\Models\Order $order
     * @param string            $message
     */
    private function _setChangedStatus(Order $order, $message)
    {
        $this->orderService->addSystemOrderComment($order, $message, 'changed');

        $order->status = 'changed';
        $order->save();
    }
}
