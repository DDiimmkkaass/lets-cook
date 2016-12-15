<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Models;

use App;

/**
 * Trail WithTranslationsScope
 * Add translation scope to class
 *
 * @package App\Http\Traits
 */
trait WithTranslationsTrait
{
    
    /**
     * @param $q
     */
    public function scopeWithTranslations($q)
    {
        $q->with(
            [
                'translations' => function ($query) {
                    $query->where('locale', App::getLocale());
                },
            ]
        );
    }
    
    /**
     * @param             $q
     * @param string|null $modelTable
     * @param string|null $translationsTable
     * @param string|null $modelTableKey
     * @param string|null $translationsTableKey
     */
    public function scopeJoinTranslations(
        $q,
        $modelTable = null,
        $translationsTable = null,
        $modelTableKey = null,
        $translationsTableKey = null
    ) {
        if (!$modelTable) {
            $modelTable = $this->getTable();
        }
        
        $singularModelTable = str_singular($modelTable);
        
        if (!$translationsTable) {
            $translationsTable = $singularModelTable."_translations";
        }
        
        $translationsTableKey = (empty($translationsTableKey) ? $singularModelTable."_id" : $translationsTableKey);
        $modelTableKey = (empty($modelTableKey) ? "id" : $modelTableKey);
        
        $q->leftJoin(
            $translationsTable,
            function ($join) use ($modelTable, $translationsTable, $translationsTableKey, $modelTableKey) {
                $join->on("$translationsTable.$translationsTableKey", '=', "$modelTable.$modelTableKey")
                    ->where('locale', '=', App::getLocale());
            }
        );
    }
}
