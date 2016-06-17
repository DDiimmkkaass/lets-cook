<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Exceptions\LikableClassNotExistException;
use App\Http\Requests\Frontend\Like\LikeCreateRequest;
use App\Models\Like;
use Exception;

/**
 * Class LikeController
 * @package App\Http\Controllers\Frontend
 */
class LikeController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'like';

    /**
     * @param \App\Http\Requests\Frontend\Like\LikeCreateRequest $request
     *
     * @return array
     */
    public function store(LikeCreateRequest $request)
    {
        try {
            $model = '\App\Models\\'.studly_case($request->get('likable_type'));

            if (!class_exists($model)) {
                throw new LikableClassNotExistException(trans('messages.likable class not exists'));
            }

            $model = $model::whereId($request->get('likable_id'))->firstOrFail();

            $like = $model->likes()->whereUserId($this->user->id)->first();

            if ($like) {
                $like->delete();

                return [
                    'status'  => 'success',
                    'message' => trans('messages.like successfully deleted'),
                    'count'   => count($model->likes),
                ];
            }

            $like = new Like();
            $like->user_id = $this->user->id;
            $model->likes()->save($like);

            return [
                'status'  => 'success',
                'message' => trans('messages.like successfully saved'),
                'count'   => count($model->likes),
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
        }
    }
}