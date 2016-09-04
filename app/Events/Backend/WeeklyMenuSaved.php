<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Events\Backend;

use App\Events\Event;
use App\Models\WeeklyMenu;
use Illuminate\Queue\SerializesModels;

/**
 * Class WeeklyMenuSaved
 * @package App\Events\Backend
 */
class WeeklyMenuSaved extends Event
{

    use SerializesModels;

    /**
     * @var WeeklyMenu
     */
    public $weekly_menu;
    
    /**
     * Create a new event instance.
     *
     * @param \App\Models\WeeklyMenu $weekly_menu
     *
     * @internal param int $page_id
     */
    public function __construct(WeeklyMenu $weekly_menu)
    {
        $this->weekly_menu = $weekly_menu;
    }
}
