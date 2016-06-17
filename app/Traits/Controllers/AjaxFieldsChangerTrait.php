<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Controllers;

use App\Events\Event;
use Request;
use Response;

/**
 * url for controller: (post) controller_name/ajax_field/{id}
 *
 * Class AjaxFieldsChangerTrait
 * @package App\Traits\Controllers
 */
trait AjaxFieldsChangerTrait
{

    /**
     * change field = $field of record with id = $id
     *
     * @param $id
     *
     * @return json array
     */
    public function ajaxFieldChange($id)
    {
        $class_name = get_model_by_controller(__CLASS__);
        $class = '\App\Models\\'.$class_name;
        $model = new $class();

        $model = $model::find($id);

        if ($model) {
            $field = Request::get('field');
            $value = Request::get('value');

            if (!is_null($value)) {
                $model->{$field} = $value;
            }

            if ($model->save()) {
                $event = '\App\Events\Backend\\'.$class_name.'Edit';
                if (class_exists($event)) {
                    Event::fire(new $event($model));
                }

                return Response::json(
                    ["error" => 0, 'message' => trans('messages.field_value_successfully_saved'), 'type' => 'success']
                );
            }
        }

        return Response::json(
            ["error" => 1, 'message' => trans('messages.error_in_field_value_saving'), 'type' => 'error']
        );
    }
} 