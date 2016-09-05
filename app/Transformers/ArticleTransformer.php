<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 2:49
 */

namespace App\Transformers;

use App\Contracts\Transformer;

/**
 * Class ArticleTransformer
 * @package App\Transformers
 */
class ArticleTransformer implements Transformer
{
    
    /**
     * @param $model
     *
     * @return array
     */
    public static function transform($model)
    {
        return [
            'id'          => $model->id,
            'name'        => $model->name,
            'image'       => thumb($model->image, 220, 120),
            'description' => $model->getShortContent(),
            'href'        => $model->getUrl(),
            'tags'        => self::tags($model),
        ];
    }
    
    /**
     * @param $model
     *
     * @return array
     */
    private static function tags($model)
    {
        $tags = [];

        foreach ($model->tags as $tag) {
            $tags[] = [
                'id'   => $tag->tag->id,
                'name' => $tag->tag->name,
            ];
        }
        
        return $tags;
    }
}