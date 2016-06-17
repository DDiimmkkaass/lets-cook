<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Widgets\TextWidget;

use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\View;
use App\Models\TextWidget;

/**
 * Class TextWidgetWidget
 * @package App\Widgets\TextWidget
 */
class TextWidgetWidget extends Widget
{
    /**
     * @param string $position
     * @param string $delimiter
     */
    function index($position, $delimiter = '')
    {
        $list = TextWidget::where('layout_position', $position)->visible()->positionSorted()->get();

        return View::make('widgets.text_widget.index')->with(compact("list", "delimiter"));
    }
}
 