<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:41
 */

namespace App\Services;

use App\Models\Article;
use App\Transformers\ArticleTransformer;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ArticleService
 * @package App\Services
 */
class ArticleService
{
    
    /**
     * @param \App\Models\Article $model
     */
    public function setExternalUrl(Article $model)
    {
        $model->external_url = get_hashed_url($model, 'article');
        
        $model->save();
    }
    
    /**
     * @return LengthAwarePaginator
     */
    public function getList()
    {
        $list = Article::with(['translations', 'tags', 'tags.tag.translations'])
            ->visible()
            ->publishAtSorted()
            ->positionSorted();
        
        $list = $this->_implodeFilters($list);
        
        if (request('page', 1) == 0) {
            $list = $list->get();
        } else {
            $list = $list->paginate(config('articles.per_page'));
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
        $category_id = request()->route('category_id', 0);
        $tag_id = request()->route('tag_id', 0);
        
        if ($category_id > 0) {
            $list->whereExists(
                function ($query) use ($category_id, $tag_id) {
                    $query->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id')
                        ->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            'articles.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', Article::class).'\' AND
                            tags.category_id = '.$category_id.
                            ($tag_id > 0 ? ' AND tagged.tag_id = '.$tag_id : '')
                        );
                }
            );
        }
        
        if (!$category_id && $tag_id > 0) {
            $list->whereExists(
                function ($query) use ($tag_id) {
                    $query->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            'articles.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', Article::class).'\' AND
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
        $data = ['articles' => []];
        
        foreach ($list as $item) {
            $data['articles'][] = ArticleTransformer::transform($item);
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
    
        $data['next_count_label'] = trans_choice('labels.count_of_articles', $data['next_count']);
        
        return $data;
    }
}