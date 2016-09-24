<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\BasketSubscribeUpdated;
use App\Models\Order;
use App\Services\SubscribeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class UpdateTmplOrders
 * @package App\Listeners\Events\Frontend
 */
class UpdateTmplOrders implements ShouldQueue
{

    use InteractsWithQueue;
    
    /**
     * @var \App\Services\SubscribeService
     */
    private $subscribeService;
    
    /**
     * UpdateTmplOrders constructor.
     *
     * @param \App\Services\SubscribeService $subscribeService
     */
    public function __construct(SubscribeService $subscribeService)
    {
        $this->subscribeService = $subscribeService;
    }
    
    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\BasketSubscribeUpdated $event
     */
    public function handle(BasketSubscribeUpdated $event)
    {
        Order::ofStatus('tmpl')
            ->whereUserId($event->subscribe->user_id)
            ->delete();
    
        $this->subscribeService->generateTmplOrders($event->subscribe);
    }
}