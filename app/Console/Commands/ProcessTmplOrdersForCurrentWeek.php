<?php

namespace App\Console\Commands;

use App\Events\Backend\TmplOrderPaymentError;
use App\Events\Backend\TmplOrderSuccessfullyPaid;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Event;
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
                $order->total = $order->getTotal();
                
                $this->log('process order #'.$order->id, 'info', $order->toArray());
                
                $result = $this->paymentService->automaticallyPay($order);
                
                if ($result === true) {
                    $order->status = 'paid';
                    $order->save();
                    
                    $this->orderService->addSystemOrderComment(
                        $order,
                        trans('messages.order successfully auto paid'),
                        'paid'
                    );
                    
                    $this->log('order #'.$order->id.' successfully paid, order status changed to "paid"', 'info');
                    
                    Event::fire(new TmplOrderSuccessfullyPaid($order));
                    
                    continue;
                }
                
                $message = 'order #'.$order->id.' online paid error: '.$result['message'];
                $this->log($message, 'error');
                
                $order->status = 'changed';
                $order->save();
    
                $this->orderService->addSystemOrderComment(
                    $order,
                    trans('messages.order auto paid failed').': '.$result['message'],
                    'changed'
                );
                
                $this->log('order #'.$order->id.' status changed to "changed"', 'info');
                
                Event::fire(new TmplOrderPaymentError($order, $result['message']));
            } catch (Exception $e) {
                $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
                
                admin_notify($this->description.' error: '.$message);
            }
        }
        
        $this->log('End '.$this->description);
    }
}
