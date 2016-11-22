<?php

namespace App\Console\Commands;

use App\Events\Backend\CompletedOrderArchived;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use DB;
use Exception;

/**
 * Class ArchiveCompletedOrders
 * @package App\Console\Commands
 */
class ArchiveCompletedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:archive-completed-orders';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive completed on current week orders';
    
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
        
        $delivery_date = Carbon::now()->subDay()->startOfDay();
        
        foreach (Order::with('user')->ofStatus('processed')->whereDeliveryDate($delivery_date)->get() as $order) {
            try {
                DB::beginTransaction();
                
                $order->status = Order::getStatusIdByName('archived');
                $order->save();
    
                event(new CompletedOrderArchived($order));
                
                $this->orderService->addSystemOrderComment(
                    $order,
                    trans('messages.archive completed order'),
                    'archived'
                );
    
                DB::commit();
                
                $this->log(
                    'competed order #'.$order->id.' with delivery date = '.$order->delivery_date.', successfully archived, order status changed to "archived"',
                    'info'
                );
            } catch (Exception $e) {
                DB::rollBack();
                
                $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
    
                $this->log($message, 'error');
                
                admin_notify($this->description.' error: '.$message);
            }
        }
        
        $this->log('End '.$this->description);
    }
}
