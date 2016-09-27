<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Event;
use Sentry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class AddToClientsGroup
 * @package App\Listeners\Events\Frontend
 */
class AddToClientsGroup implements ShouldQueue
{

    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param \App\Events\Event $event
     */
    public function handle(Event $event)
    {
        $clients_group = Sentry::getGroupProvider()->findByName('Clients');
        
        if ($clients_group) {
            $event->user->groups()->sync([$clients_group->id]);
        }
    }
}