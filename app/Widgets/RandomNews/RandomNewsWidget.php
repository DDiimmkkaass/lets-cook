<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 24.03.16
 * Time: 23:11
 */

namespace App\Widgets\RandomNews;

use App\Models\News;
use Pingpong\Widget\Widget;

/**
 * Class RandomNewsWidget
 * @package App\Widgets\RandomNews
 */
class RandomNewsWidget extends Widget
{

    /**
     * @var string
     */
    protected $view = 'default';

    /**
     * @param null $template
     * @param int  $count
     *
     * @return mixed
     */
    public function index($template = null, $count = 4)
    {
        $list = News::visible()->get(['id']);

        $news_count = count($list);

        if ($news_count > $count) {
            $ids = $list->random($count)->pluck('id')->toArray();

            $list = News::with('translations')->whereIn('id', $ids)->get();
        } else {
            $list = News::with('translations')->get();
        }

        if (view()->exists('widgets.random_news.templates.'.$template.'.index')) {
            $this->view = $template;
        }

        return view('widgets.random_news.templates.'.$this->view.'.index')->with('list', $list)->render();
    }
}