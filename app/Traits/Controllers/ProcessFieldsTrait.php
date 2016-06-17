<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Controllers;

use App\Models\Field;
use Exception;
use Request;

/**
 * Class ProcessFieldsTrait
 * @package App\Traits\Controllers
 */
trait ProcessFieldsTrait
{

    /**
     * @param $model
     *
     * @throws \Exception
     */
    public function processFields($model)
    {
        $this->_removeExists($model);

        $this->_updateExists($model);

        $this->_saveNew($model);
    }

    /**
     * @param $model
     *
     * @throws \Exception
     */
    private function _removeExists($model)
    {

        $data = Request::get('fields', []);

        if (!empty($data['remove'])) {
            foreach ($data['remove'] as $id) {
                try {
                    $field = $model->fields()->findOrFail($id);
                    $field->delete();
                } catch (Exception $e) {
                    throw new Exception(trans('messages.failed_field_removing').' (id = '.$id.'): '.$e->getMessage());
                }
            }
        }
    }

    /**
     * @param $model
     *
     * @throws \Exception
     */
    private function _updateExists($model)
    {
        $data = Request::get('fields', []);

        if (!empty($data['aol'])) {
            foreach ($data['old'] as $id => $val) {
                if (!empty($val['type'])) {
                    try {
                        $field = $model->fields()->findOrFail($id);
                        $field->update($val);
                    } catch (Exception $e) {
                        throw new Exception(
                            trans('messages.failed_field_updating').' ('.implode(', ', $val).'): '.$e->getMessage()
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $model
     *
     * @throws \Exception
     */
    private function _saveNew($model)
    {
        $data = Request::get('fields', []);

        if (!empty($data['new'])) {
            foreach ($data['new'] as $val) {
                if (!empty($val['type']) && $val['type'] > 0) {
                    $field = new Field($val);
                    try {
                        $model->fields()->save($field);
                    } catch (Exception $e) {
                        throw new Exception(
                            trans('messages.failed_field_saving').' (): '.$e->getMessage()
                        );
                    }
                }
            }
        }
    }
}