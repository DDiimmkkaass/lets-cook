<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Observers;

use App\Models\Order;
use App\Services\PurchaseService;

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
     * OrderObserver constructor.
     *
     * @param \App\Services\PurchaseService $purchaseService
     */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
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
                (isset($model->original['status']) && $model->isOriginalStatus('processed')))
        ) {
            if (after_finalisation()) {
                $this->purchaseService->generate();
            }
        }
    }
}