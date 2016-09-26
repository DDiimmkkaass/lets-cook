<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PurchaseService;
use Carbon;

/**
 * Class OrderObserver
 * @package App\Observers
 */
class OrderObserver
{
    
    /**
     * @var \App\Services\PurchaseService
     */
    private $purchaseService;
    
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * OrderObserver constructor.
     *
     * @param \App\Services\PurchaseService $purchaseService
     * @param \App\Services\OrderService    $orderService
     */
    public function __construct(PurchaseService $purchaseService, OrderService $orderService)
    {
        $this->purchaseService = $purchaseService;
        $this->orderService = $orderService;
    }
    
    /**
     * @param \App\Models\Order $model
     */
    public function saving(Order $model)
    {
        if ($model->isStatus('deleted')) {
            $model->coupon_id = null;
    
            list($subtotal, $total) = $this->orderService->getTotals($model);
    
            $model->subtotal = $subtotal;
            $model->total = $total;
        }
    }
    
    /**
     * @param Order $model
     */
    public function saved(Order $model)
    {
        if (
            $model->forCurrentWeek()
            &&
            (
                $model->isStatus('processed')
                ||
                (isset($model->original['status']) && $model->isOriginalStatus('processed'))
            )
        ) {
            $dt = active_week();
            
            if (after_finalisation($dt->year, $dt->weekOfYear)) {
                $this->purchaseService->generate();
            }
        }
    }
}