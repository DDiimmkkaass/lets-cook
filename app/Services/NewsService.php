<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:41
 */

namespace App\Services;

use App\Models\News;
use App\Transformers\NewsTransformer;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class NewsService
 * @package App\Services
 */
class NewsService
{
    
    /**
     * @param \App\Models\News $model
     */
    public function setExternalUrl(News $model)
    {
        $model->external_url = get_hashed_url($model, 'news');
        
        $model->save();
    }
    
    /**
     * @return LengthAwarePaginator
     */
    public function getList()
    {
        $list = News::with(['translations', 'tags', 'tags.tag.translations'])
            ->visible()
            ->publishAtSorted()
            ->positionSorted();
        
        $list = $this->_implodeFilters($list);
        
        if (request('page', 1) == 0) {
            $list = $list->get();
        } else {
            $list = $list->paginate(config('news.per_page'));
        }
    
        $list = $this->_prepareData($list);
        
        return $list;
    }
    
    /**
     * @param Builder $list
     *
     * @return Builder
     */
    private function _implodeFilters(Builder $list)
    {
        $tag_id = request()->route('tag_id', 0);
        
        if ($tag_id > 0) {
            $list->whereExists(
                function ($query) use ($tag_id) {
                    $query->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            'news.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', News::class).'\' AND
                            tagged.tag_id = '.$tag_id
                        );
                }
            );
        }
        
        return $list;
    }
    
    /**
     * @param $list
     *
     * @return array
     */
    private function _prepareData($list)
    {
        $data = ['blog' => []];
        
        foreach ($list as $item) {
            $data['blog'][] = NewsTransformer::transform($item);
        }
        
        if ($list instanceof Collection) {
            $data['next_count'] = 0;
        } else {
            if ($list->lastPage() == $list->currentPage()) {
                $data['next_count'] = 0;
            } else {
                $data['next_count'] = $list->total() - $list->currentPage() * $list->perPage();
                $data['next_count'] = $data['next_count'] > $list->perPage() ? $list->perPage() : $data['next_count'];
    
                $data['next_count'] = $data['next_count'] >= 0 ? $data['next_count'] : 0;
            }
        }
    
        $data['next_count_label'] = trans('front_labels.pagination_next').' '.
            $data['next_count'].' '.
            trans_choice('front_labels.count_of_news', $data['next_count']);
        
        return $data;
    }
}