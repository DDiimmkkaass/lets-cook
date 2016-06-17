<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 21.03.16
 * Time: 22:41
 */

namespace App\Widgets\LikeButton;

use App\Contracts\Likable;
use Sentry;
use Pingpong\Widget\Widget;

/**
 * Class LikeButtonWidget
 * @package App\Widgets\LikeButton
 */
class LikeButtonWidget extends Widget
{
    
    /**
     * @param \App\Contracts\Likable $model
     * @param string                 $template
     *
     * @return string
     */
    public function index(Likable $model, $template = '')
    {
        // current user
        $user_id = Sentry::getUser() ? Sentry::getUser()->getId() : 0;

        // check item likable for current user
        $likable = $user_id ? true : false;

        // likes count
        $likes = count($model->likes);

        // check if current model is liked from current user
        $liked = false;
        array_map(function($item) use (&$liked, $user_id) {
            $liked = $item['user_id'] == $user_id;
        }, $model->likes->toArray());

        // likable type
        $type = snake_case(get_class_name_from_namespace($model));

        // likable id
        $id = $model->id;
        
        return $template ?
            view('widgets.like_button.templates.'.$template.'.index')
                ->with(compact('liked', 'likable', 'type', 'id', 'likes'))
                ->render() :
            '';
    }
    
}