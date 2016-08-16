<?php

namespace App\Console\Commands;

use App\Events\Backend\TmplOrderPaymentError;
use App\Events\Backend\TmplOrderSuccessfullyPaid;
use App\Models\Order;
use App\Services\PaymentService;
use Event;
use Exception;

/**
 * Class ProcessTmplOrdersForNextWeek
 * @package App\Console\Commands
 */
class ProcessTmplOrdersForNextWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-tmp-orders-for-next-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process tmpl orders with delivery for the next week';
    
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * Create a new command instance.
     *
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log('Start '.$this->description);
        
        foreach (Order::ofStatus('tmpl')->forNextWeek()->get() as $order) {
            try {
                $this->log('process order #'.$order->id, 'info', $order->toArray());
                
                $result = $this->paymentService->automaticallyPay($order);
                
                if ($result === true) {
                    $order->status = 'paid';
                    $order->save();

                    $this->log('order #'.$order->id.' successfully paid, order status changed to "paid"', 'info');

                    Event::fire(new TmplOrderSuccessfullyPaid($order));

                    continue;
                }
    
                $message = 'order #'.$order->id.' online paid error: '.$result['message'];
                $this->log($message, 'error');
                
                $order->status = 'changed';
                $order->admin_comment = trans('messages.tmpl order payment error message').': '.$result['message'];
                $order->save();
                
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
