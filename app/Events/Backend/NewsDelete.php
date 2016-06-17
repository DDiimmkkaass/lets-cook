<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:38
 */

namespace App\Events\Backend;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewsDelete
 * @package App\Events\Backend
 */
class NewsDelete extends Event
{

    use SerializesModels;

    /**
     * @var int
     */
    public $news_id;

    /**
     * Create a new event instance.
     *
     * @param int $news_id
     */
    public function __construct($news_id)
    {

        $this->news_id = $news_id;
    }
}
