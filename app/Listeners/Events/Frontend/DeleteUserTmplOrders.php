<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\BasketSubscribeDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class DeleteUserTmplOrders
 * @package App\Listeners\Events\Frontend
 */
class DeleteUserTmplOrders implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\BasketSubscribeDeleted $event
     */
    public function handle(BasketSubscribeDeleted $event)
    {
        $event->user->orders()->ofStatus('tmpl')->delete();
    }
}