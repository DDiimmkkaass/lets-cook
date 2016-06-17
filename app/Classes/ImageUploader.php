<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Classes;

use File;

/**
 * Class ImageUploader
 * @package App\Libraries
 */
class ImageUploader
{

    /**
     * @var bool
     */
    public $overwrite = false;

    /**
     * @param File   $file
     * @param string $module
     * @param null   $dir
     *
     * @return string
     */
    public function upload($file, $module = "page", $dir = null)
    {
        if ($file) {
            $filename = md5(microtime(true).$file->getClientOriginalName()).".".strtolower(
                    $file->getClientOriginalExtension()
                );

            $dir = $this->_getRandomDir($dir, $filename);

            $destination = public_path().'/uploads/images/'.$module.'/'.$dir;
            $path = '/uploads/images/'.$module.'/'.$dir.'/'.$filename;

            $uploaded = $file->move($destination, $filename);

            if ($uploaded) {
                return $path;
            }
        }
    }

    /**
     * @param string $filePath
     * @param string $module
     * @param null   $dir
     *
     * @return string
     *
     * Copy local file on server
     */
    public function copy($filePath, $module = "page", $dir = null)
    {
        if ($filePath) {
            $pathinfo = pathinfo($filePath);
            $filename = md5(microtime(true).$pathinfo['filename']).".".strtolower($pathinfo['extension']);

            $dir = $this->_getRandomDir($dir, $filename);

            $destination = public_path().'/uploads/images/'.$module.'/'.$dir;
            $path = '/uploads/images/'.$module.'/'.$dir.'/'.$filename;

            if (!File::exists($destination)) {
                @File::makeDirectory($destination, 0755, true);
            }

            @File::copy($filePath, $destination.'/'.$filename);

            return $path;
        }

        return false;
    }

    /**
     * @param File   $file
     * @param array  $crop_options
     * @param string $module
     * @param null   $dir
     *
     * @return bool|string
     */
    public function crop($file, $crop_options = [], $module = "page", $dir = null)
    {
        if ($file && !empty($crop_options)) {
            $filename = md5(microtime(true).$file->getClientOriginalName()).".".strtolower(
                    $file->getClientOriginalExtension()
                );

            $dir = $this->_getRandomDir($dir, $filename);

            $destination = public_path().'/uploads/images/'.$module.'/'.$dir;
            $path = '/uploads/images/'.$module.'/'.$dir.'/'.$filename;

            $origin_width = getimagesize($file->getRealPath())[0];
            $origin_height = getimagesize($file->getRealPath())[1];

            $crop_options = [
                'x'      => empty($crop_options['x']) ? 0 : $crop_options['x'],
                'y'      => empty($crop_options['y']) ? 0 : $crop_options['y'],
                'width'  => min($origin_width, empty($crop_options['width']) ? $origin_width : $crop_options['width']),
                'height' => min(
                    $origin_height,
                    empty($crop_options['height']) ? $origin_height : $crop_options['height']
                ),
            ];

            $tmp_img = imagecrop($this->_createImage($file), $crop_options);

            if (imagepng($tmp_img, $file->getRealPath())) {
                if ($file->move($destination, $filename)) {
                    return $path;
                }
            }
        }

        return false;
    }

    /**
     * @param string $dir
     * @param string $filename
     *
     * @return string
     */
    private function _getRandomDir($dir, $filename)
    {
        if (is_null($dir)) {
            $dir = substr($filename, 0, 2).'/'.substr($filename, 2, 2);

            return $dir;
        }

        return $dir;
    }

    /**
     * @param File $file
     *
     * @return mixed
     */
    private function _createImage($file)
    {
        $image = '';

        switch ($file->getClientOriginalExtension()) {
            case 'jpg' :
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'jpeg' :
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png' :
                $image = imagecreatefrompng($file->getRealPath());
                break;
        }

        return $image;
    }
}