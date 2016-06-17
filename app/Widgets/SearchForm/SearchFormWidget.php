<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Widgets\SearchForm;

use App\Models\SearchIndex;
use Pingpong\Widget\Widget;

/**
 * Class SearchFormWidget
 * @package App\Widgets\SearchForm
 */
class SearchFormWidget extends Widget
{

    /**
     * @param bool $with_filters
     *
     * @return mixed
     */
    function index($with_filters = false)
    {
        $types = [];

        foreach (SearchIndex::getTypes() as $type) {
            $type = snake_case(get_class_name_from_namespace($type));

            $types[$type] = trans('labels.search_type_'.$type);
        }

        view()->share('search_text', request('search_text', ''));
        view()->share('search_types', $types);

        return view('widgets.search_form.index')->with(['with_filters' => $with_filters])->render();
    }
}