<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Widgets\TagsFilter;

use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\View;
use App\Models\Tag;

/**
 * Class TagsFilterWidget
 * @package App\Widgets\TagsFilter
 */
class TagsFilterWidget extends Widget
{

    function index($model = 'page')
    {
        $list = Tag::withTranslations()->visible()->positionSorted()->get();

        return View::make('widgets.tags_filter.index')->with(compact(['list', 'model']));
    }
}
 