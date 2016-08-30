<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:41
 */

namespace App\Services;

use App\Models\Article;
use App\Models\Tagged;
use Cache;
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
        $list = Article::withTranslations()->visible()->publishAtSorted()->positionSorted();

        $list = $this->_implodeFilters($list);

        return $list->paginate(config('articles.per_page'));
    }

    /**
     * @param Builder $list
     *
     * @return Builder
     */
    private function _implodeFilters(Builder $list)
    {
        if (request('tag')) {
            $list->whereExists(
                function ($query) {
                    $query
                        ->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id')
                        ->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            '
                            articles.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', Article::class).'\' AND
                            tags.slug = \''.request('tag').'\''
                        );
                }
            );
        }

        return $list;
    }

    /**
     * @param \App\Models\Article $model
     * @param int              $count
     *
     * @return Collection
     */
    public function getRelatedArticlesForArticle(Article $model, $count = 4)
    {
        $model->related_articles = unserialize(Cache::get('related_articles_for_article_'.$model->id, false));

        if ($model->related_articles === false) {
            if (count($model->tags)) {
                $tagged = Tagged::whereIn('tag_id', array_pluck($model->tags->toArray(), 'tag_id'))
                    ->where('taggable_id', '<>', $model->id)
                    ->whereRaw('taggable_type = \''.str_replace('\\', '\\\\', Article::class).'\'')
                    ->get();

                if (count($tagged) > $count) {
                    $tagged = $tagged->random($count);

                    if (count($tagged) == 1) {
                        $tagged = Collection::make([$tagged]);
                    }
                }

                $model->related_articles = Article::with('translations')
                    ->whereIn('id', array_pluck($tagged->toArray(), 'taggable_id'))
                    ->get();
            } else {
                $model->related_articles = [];
            }

            Cache::add('related_articles_for_article_'.$model->id,
                serialize($model->related_articles),
                config('articles.related_articles_cache_time')
            );
        }

        return $model->related_articles;
    }
}