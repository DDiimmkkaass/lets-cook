<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Controllers;

use Exception;
use Input;
use App\Models\Media;

/**
 * Class ProcessMediaTrait
 * @package App\Traits
 */
trait ProcessMediaTrait
{

    /**
     * @param        $model
     * @param string $type
     *
     * @throws \Exception
     */
    public function processMedia($model, $type = 'image')
    {
        $this->_removeExists($type);

        $this->_updateExists($type);

        $this->_saveNew($model, $type);
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     */
    private function _removeExists($type)
    {

        $array = 'remove'.str_plural(mb_convert_case($type, MB_CASE_TITLE, "UTF-8"));

        $list = Input::get($array, []);

        if (sizeof($list)) {
            foreach ($list as $id) {
                try {
                    $media = Media::findOrFail($id);
                    $media->delete();
                } catch (Exception $e) {
                    throw new Exception(trans('messages.failed_media_removing').' ('.$type.'): '.$e->getMessage());
                }
            }
        }
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     */
    private function _updateExists($type)
    {
        $array = str_plural($type).'.old';

        $data = Input::get($array, []);

        if (sizeof($data)) {
            foreach ($data as $key => $val) {
                if (!empty($val['src'])) {
                    try {
                        $media = Media::findOrFail($key);
                        $media->update($val);
                    } catch (Exception $e) {
                        throw new Exception(trans('messages.failed_media_updating').' ('.$type.'): '.$e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * @param        $model
     * @param string $type
     *
     * @throws \Exception
     */
    private function _saveNew($model, $type)
    {
        $array = str_plural($type).'.new';

        $data = Input::get($array, []);

        if (sizeof($data)) {
            foreach ($data as $val) {
                if (!empty($val['src'])) {
                    try {
                        $media = new Media($val);

                        $model->media()->save($media);
                    } catch (Exception $e) {
                        throw new Exception(trans('messages.failed_media_saving').' ('.$type.'): '.$e->getMessage());
                    }
                }
            }
        }
    }
}