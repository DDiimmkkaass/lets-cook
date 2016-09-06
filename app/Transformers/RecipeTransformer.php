<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.09.16
 * Time: 2:49
 */

namespace App\Transformers;

use App\Contracts\Transformer;
use App\Models\Recipe;

/**
 * Class RecipeTransformer
 * @package App\Transformers
 */
class RecipeTransformer implements Transformer
{
    
    /**
     * @param $model
     *
     * @return array
     */
    public static function transform($model)
    {
        return [
            'id'           => $model->id,
            'name'         => $model->name,
            'image'        => thumb($model->image, 220, 120),
            'href'         => $model->getUrl(),
            'tags'         => self::tags($model),
            'cooking_time' => $model->cooking_time,
            'rating'       => $model->getRating(),
            'description'  => self::ingredients($model),
        ];
    }
    
    /**
     * @param \App\Models\Recipe $model
     *
     * @return array
     */
    private static function tags(Recipe $model)
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
    
    /**
     * @param \App\Models\Recipe $model
     *
     * @return string
     */
    private static function ingredients(Recipe $model)
    {
        $ingredients = [];
        
        foreach ($model->ingredients as $ingredient) {
            $ingredients[] = $ingredient->ingredient->name;
        }
        
        return implode(', ', $ingredients);
    }
}