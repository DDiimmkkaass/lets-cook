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
     * @param      $q
     * @param      $modelTable
     * @param null $translationsTable
     * @param null $modelTableKey
     * @param null $translationsTableKey
     */
    public function scopeJoinTranslations(
        $q,
        $modelTable,
        $translationsTable = null,
        $modelTableKey = null,
        $translationsTableKey = null
    ) {
        $class = get_class($this);
        $class = explode('\\', $class);

        $model = mb_convert_case(array_pop($class), MB_CASE_LOWER);

        if (!$translationsTable) {
            $translationsTable = $model."_translations";
        }

        $translationsTableKey = (empty($translationsTableKey) ? $model."_id" : $translationsTableKey);
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
