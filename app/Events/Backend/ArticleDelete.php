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
 * Class ArticleDelete
 * @package App\Events\Backend
 */
class ArticleDelete extends Event
{

    use SerializesModels;

    /**
     * @var int
     */
    public $article_id;

    /**
     * Create a new event instance.
     *
     * @param int $article_id
     */
    public function __construct($article_id)
    {
        $this->article_id = $article_id;
    }
}
