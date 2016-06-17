<?php namespace App\Http\Controllers\Frontend;

use App\Models\SearchIndex;
use FlashMessages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SearchController
 * @package App\Http\Controllers\Frontend
 */
class SearchController extends FrontendController
{
    /**
     * @var string
     */
    public $module = 'search';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return $this|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $search_text = $request->get('search_text', '');
        $type = $request->get('type', null);
        $list = [];

        if (mb_strlen($search_text) >= config('search.min_search_text_length')) {
            $list = $total = SearchIndex::withTranslations()
                ->of($type)
                ->search($search_text);

            $total = count($total->get());

            if ($total) {
                $list = $this->_paginate($list, $total, ['search_text' => $search_text, 'type' => $type]);
            } else {
                $list = [];
            }
        } else {
            FlashMessages::add('warning', trans('messages.min search text length error'));
        }

        $this->data('list', $list);
        $this->data('index_modifier', (request('page', 1) -1) * config('search.per_page') + 1);

        return $this->render('views.search.index');
    }

    /**
     * @param Builder $list
     * @param int     $total
     * @param array   $params
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function _paginate($list, $total, $params)
    {
        $page = request('page', 1);

        $list = $list->take(config('search.per_page'))->skip(($page - 1) * config('search.per_page'))->get();

        return new LengthAwarePaginator(
            $list,
            $total,
            config('search.per_page'),
            $page,
            [
                'path'  => route('search.index'),
                'query' => $params,
            ]
        );
    }
}