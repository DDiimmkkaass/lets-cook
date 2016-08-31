<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 24.03.16
 * Time: 23:11
 */

namespace App\Widgets\LastArticles;

use App\Models\Article;
use Pingpong\Widget\Widget;

/**
 * Class LastArticlesWidget
 * @package App\Widgets\LastArticles
 */
class LastArticlesWidget extends Widget
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
        $list = Article::withTranslations()->visible()->publishAtSorted()->positionSorted()->take($count)->get();

        if (view()->exists('widgets.last_articles.templates.'.$template.'.index')) {
            $this->view = $template;
        }

        return view('widgets.last_articles.templates.'.$this->view.'.index')->with('list', $list)->render();
    }
}