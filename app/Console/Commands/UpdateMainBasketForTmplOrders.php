<?php

namespace App\Console\Commands;

use App\Models\BasketSubscribe;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;

/**
 * Class UpdateMainBasketForTmplOrders
 * @package App\Console\Commands
 */
class UpdateMainBasketForTmplOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-main-basket-form-tmp-orders';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update empty main baskets for tmpl orders';
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * @var array
     */
    private $subscribes = [];
    
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
        
        $now = Carbon::now();
        
        $from = clone($now->startOfWeek());
        $to = $now->addWeeks(2);
        
        $orders = Order::ofStatus('tmpl')
            ->where('delivery_date', '>=', $from)->where('delivery_date', '<=', $to)
            ->get();
        
        foreach ($orders as $order) {
            if (!$order->main_basket()->count()) {
                $this->log('start update order #'.$order->id, 'info');
    
                $subscribe = $this->_getSubscribe($order);
    
                try {
                    $basket = $this->orderService->tmplAddMainBasket($order, $subscribe);
        
                    if ($basket) {
                        $this->orderService->updatePrices($order);
            
                        list($subtotal, $total) = $this->orderService->getTotals($order);
            
                        $order->subtotal = $subtotal;
                        $order->total = $total;
            
                        $order->save();
            
                        $this->log(
                            'order successfully updated total = '.$order->total.', main basket #'.$basket->id,
                            'info'
                        );
                    } else {
                        $this->log('order update fail main basket have not yet created', 'error');
                    }
                } catch (Exception $e) {
                    $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
        
                    $this->log($message, 'error');
        
                    admin_notify($this->description.' error: '.$message);
                }
            }
        }
        
        $this->log('End '.$this->description);
    }
    
    /**
     * @param Order $order
     *
     * @return BasketSubscribe
     */
    private function _getSubscribe(Order $order)
    {
        if (!isset($this->subscribes[$order->user_id])) {
            $this->subscribes[$order->user_id] = BasketSubscribe::whereUserId($order->user_id)->first();
        }
        
        return $this->subscribes[$order->user_id];
    }
}
