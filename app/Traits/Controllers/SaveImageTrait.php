<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Controllers;

use App\Exceptions\ImageSaveException;
use App\Exceptions\NotValidImageException;
use Exception;
use ImageUploader;
use Request;

/**
 * Class SaveImageTrait
 * @package App\Traits\Controllers
 */
trait SaveImageTrait
{

    /**
     * @param object|array $model
     * @param string       $field
     * @param string       $type
     *
     * @return bool
     * @throws \App\Exceptions\ImageSaveException
     */
    public function setImage(&$model, $field, $type = 'page')
    {
        if (Request::hasFile($field)) {
            $file = Request::file($field);

            try {
                if (is_array($model)) {
                    $model[$field] = ImageUploader::upload($file, $type, $model['id']);
                } else {
                    $model->{$field} = ImageUploader::upload($file, $type, $model->id);
                }

                return true;
            } catch (Exception $e) {
                throw new ImageSaveException(trans('messages.error file saving').'('.$field.'): '.$e->getMessage());
            }
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @return bool
     * @throws \App\Exceptions\NotValidImageException
     */
    public function validateImage($field)
    {
        if (Request::hasFile($field)) {
            $file = Request::file($field);

            $file_width = getimagesize($file->getRealPath())[0];
            $file_height = getimagesize($file->getRealPath())[1];

            if ($file->getSize() > ini_get("upload_max_filesize") * 1024 * 1024 ||
                !in_array($file->getMimeType(), config('image.allowed_image_file_type', [])) ||
                $file_width > config('image.max_upload_width') ||
                $file_height > config('image.max_upload_height')
            ) {
                throw new NotValidImageException();
            }
        }

        return true;
    }
}
