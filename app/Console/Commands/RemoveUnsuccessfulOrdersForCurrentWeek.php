<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderService;

/**
 * Class RemoveUnsuccessfulOrdersForCurrentWeek
 * @package App\Console\Commands
 */
class RemoveUnsuccessfulOrdersForCurrentWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:remove-unsuccessful-orders-for-current-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all unsuccessful(changed) orders for current week';
    
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
        
        foreach (Order::ofStatus('changed')->forCurrentWeek()->get() as $order) {
            $order->status = Order::getStatusIdByName('deleted');
            $order->save();
            
            $this->orderService->addSystemOrderComment(
                $order,
                trans('messages.deleted, because payment was not made on time'),
                'deleted'
            );
            
            $this->log('unpaid order #'.$order->id.' successfully deleted, order status changed to "deleted"', 'info');
        }
        
        $this->log('End '.$this->description);
    }
}
