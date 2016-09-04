<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\WeeklyMenuSaved;
use App\Services\PurchaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class UpdatePurchase
 * @package App\Observers
 */
class UpdatePurchase implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * @var \App\Services\PurchaseService
     */
    private $purchaseService;
    
    /**
     * Create the event handler.
     *
     * @param \App\Services\PurchaseService $purchaseService
     */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    
    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle(WeeklyMenuSaved $event)
    {
        if (before_finalisation($event->weekly_menu->year, $event->weekly_menu->week)) {
            $this->purchaseService->generateFromMenu($event->weekly_menu->year, $event->weekly_menu->week);
        }
    }
}