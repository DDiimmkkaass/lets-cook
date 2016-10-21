<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderService;
use Exception;

/**
 * Class ProcessPaidOrdersForCurrentWeek
 * @package App\Console\Commands
 */
class ProcessPaidOrdersForCurrentWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-paid-orders-for-current-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set "processed" status for all "paid" orders with delivery for the current week';
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * Create a new command instance.
     *
     * @param \App\Services\OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        
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
        
        foreach (Order::ofStatus('paid')->forCurrentWeek()->get() as $order) {
            try {
                $order->status = Order::getStatusIdByName('processed');
                $order->save();
                
                $this->orderService->addSystemOrderComment(
                    $order,
                    trans('messages.paid order successfully transferred to the processing'),
                    'processed'
                );
                
                $this->log(
                    'order #'.$order->id.' successfully set processed status, order status changed to "processed"',
                    'info'
                );
            } catch (Exception $e) {
                $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
                
                $this->log($message, 'error');
                
                admin_notify($this->description.' error: '.$message);
            }
        }
        
        $this->log('End '.$this->description);
    }
}
