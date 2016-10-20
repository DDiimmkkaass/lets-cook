<?php

namespace App\Console\Commands;

use App\Events\Backend\TmplOrderPaymentError;
use App\Exceptions\AutomaticallyPayException;
use App\Exceptions\OrderConfirmException;
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
     * @return mixed
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
                    $result = $this->paymentService->automaticallyPay($order, $card);
                    
                    if ($result === true) {
                        $order->status = 'paid';
                        $order->save();
                        
                        $this->orderService->addSystemOrderComment(
                            $order,
                            trans('messages.order successfully auto paid'),
                            'paid'
                        );
                        
                        continue;
                    }
                    
                    $admin_message = trans(
                        'messages.order auto paid failed, payment system error #:error',
                        ['error' => isset($result['error']) ? $result['error'] : '']
                    );
                } else {
                    $message = trans('messages.no selected default cards user message');
                    $admin_message = trans('messages.no selected default cards admin message');
                }
            } catch (UnsupportedPaymentMethod $e) {
                $message = $admin_message = $e->getMessage();
            } catch (AutomaticallyPayException $e) {
                $admin_message = $e->getMessage();
            } catch (OrderConfirmException $e) {
                $admin_message = $e->getMessage();
            } catch (Exception $e) {
                $admin_message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
            }
            
            $admin_message = trans(
                'messages.order #:order_id auto paid failed, error: :error',
                ['order_id' => $order->id, 'error' => $admin_message]
            );
            
            $this->log('order #'.$order->id.' online paid error: '.$admin_message, 'error');
            
            $order->status = 'changed';
            $order->save();
            
            $this->log('order #'.$order->id.' status changed to "changed"', 'info');
            
            $this->orderService->addSystemOrderComment($order, $admin_message, 'changed');
            
            admin_notify($this->description.' error: '.$admin_message);
            
            if (!empty($message)) {
                event(new TmplOrderPaymentError($order, $message));
            }
        }
        
        $this->log('End '.$this->description);
    }
}
