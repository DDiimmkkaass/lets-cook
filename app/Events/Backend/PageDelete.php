<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Events\Backend;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class PageDelete
 * @package App\Events\Backend
 */
class PageDelete extends Event
{

    use SerializesModels;

    /**
     * @var int
     */
    public $page_id;

    /**
     * Create a new event instance.
     *
     * @param int $page_id
     */
    public function __construct($page_id)
    {

        $this->page_id = $page_id;
    }
}
