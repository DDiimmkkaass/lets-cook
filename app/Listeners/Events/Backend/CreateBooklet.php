<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Listeners\Events\Backend;

use App\Events\Backend\WeeklyMenuSaved;
use App\Models\Booklet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreateBooklet
 * @package App\Observers
 */
class CreateBooklet implements ShouldQueue
{
    
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle(WeeklyMenuSaved $event)
    {
        $booklet = Booklet::forWeek($event->weekly_menu->year, $event->weekly_menu->week)->first();
        
        if (!$booklet) {
            Booklet::create(
                [
                    'year' => $event->weekly_menu->year,
                    'week' => $event->weekly_menu->week,
                ]
            );
        }
    }
}