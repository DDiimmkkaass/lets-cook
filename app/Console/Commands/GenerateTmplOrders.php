<?php

namespace App\Console\Commands;

use App\Models\BasketSubscribe;
use App\Models\Order;
use App\Services\OrderService;
use Carbon;
use Exception;

/**
 * Class GenerateTmplOrders
 * @package App\Console\Commands
 */
class GenerateTmplOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:generate-tmpl-orders';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tmpl orders in advance';
    
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
        
        $check_date = Carbon::now()->addWeeks(config('order.subscribe_auto_generation_time'));
        
        $this->log('max date = '.$check_date);
        
        foreach (BasketSubscribe::with('user')->get() as $subscribe) {
            try {
                $all = false;
                
                $this->log('start process subscribe # '.$subscribe->id);
                
                $last_tmpl_order = Order::ofStatus('tmpl')
                    ->whereUserId($subscribe->user_id)
                    ->orderBy('delivery_date', 'DESC')
                    ->first();
                
                if ($last_tmpl_order) {
                    $this->log('latest tmpl order for subscribe # '.$last_tmpl_order->id);
                    
                    $all = $last_tmpl_order->getDeliveryDate() >= $check_date;
                }
                
                while (!$all) {
                    $tmpl_order = $this->orderService->createTmpl($subscribe);
                    
                    $this->log(
                        'create new tmpl order #'.$tmpl_order->id.', delivery_date = '.$tmpl_order->delivery_date
                    );
                    
                    $all = $tmpl_order->getDeliveryDate() >= $check_date;
                }
                
                $this->log('end process subscribe # '.$subscribe->id);
            } catch (Exception $e) {
                $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
                
                admin_notify($this->description.' error: '.$message);
            }
        }
        
        $this->log('End '.$this->description);
    }
}
