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
 * Class CommentTransformer
 * @package App\Transformers
 */
class CommentTransformer implements Transformer
{
    
    /**
     * @param $model
     *
     * @return array
     */
    public static function transform($model)
    {
        return [
            'id'      => $model->id,
            'name'    => $model->getUserName(),
            'image'   => thumb($model->getUserImage(), 104),
            'comment' => $model->comment,
            'date'    => $model->getDate(),
        ];
    }
}