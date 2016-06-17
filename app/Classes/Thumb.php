<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Classes;

use File;
use Image;

/**
 * Class Thumb
 * @package App\Classes\Thumb
 *
 * Use Intervention\Image methods to expand this class!
 */
class Thumb
{

    /**
     * @var null
     */
    private $img = null;

    /**
     * @var null
     */
    private $oldPath = null;

    /**
     * @var null
     */
    private $postfix = null;

    /**
     * @param $path
     *
     * @return static
     *
     * Create table method
     */
    public static function create($path)
    {

        if (strpos($path, public_path()) === false) {
            $path = public_path($path);
        }

        $ins = new static;

        if (file_exists($path)) {
            $ins->oldPath = $path;
            $img = Image::make($path);
            $ins->img = $img;
        }

        return $ins;
    }

    /**
     * @param $path
     * @param $w
     * @param $h
     *
     * @return $this
     *
     * Quick method to resize image
     */
    public static function thumb($path, $w, $h)
    {

        $img = self::create($path);

        return $img->resize($w, $h);
    }

    /**
     * @param $path
     * @param $s
     *
     * @return $this
     *
     * Quick method to create a square image
     */
    public static function square($path, $s)
    {

        $img = self::create($path);

        return $img->resize($s, $s);
    }

    /**
     * @param $w
     * @param $h
     *
     * @return $this
     *
     * Resize image
     */
    public function resize($w, $h)
    {

        if ($this->img) {
            $this->img->fit($w, $h);
            $this->postfix = "_{$w}x{$h}";
        }

        return $this;
    }

    /**
     * @return bool|mixed|string
     *
     * Return link to modified image of false if an error has occurred
     */
    public function link()
    {

        if ($this->img) {
            if (file_exists($this->getNewFilePath())) {
                return $this->getUrl();
            } else {
                if ($this->save()) {
                    return $this->getUrl();
                }
            }
        }

        return false;
    }

    /**
     * @return bool|string
     *
     * Save modified image
     */
    public function save()
    {

        if ($this->img) {
            $file = $this->getNewFilePath();

            if ($this->img->save($file)) {
                return $file;
            }
        }

        return false;
    }

    /**
     * @return string
     *
     * return new file location on disc
     */
    private function getNewFilePath()
    {

        if ($this->img) {
            $path_info = pathinfo($this->oldPath);

            $file_name = md5($path_info['filename']);
            $path = substr($file_name, 0, 2).'/'.substr($file_name, 2, 2);

            $path = public_path('thumbs/'.$path);

            if (!File::exists($path)) {
                @File::makeDirectory($path, 0755, true);
            }

            return $path.'/'.$file_name.$this->postfix.'.'.$path_info['extension'];
        }

        return '';
    }

    /**
     * @return mixed|string
     *
     * return url to new file
     */
    private function getUrl()
    {

        if ($file = $this->getNewFilePath()) {
            return str_replace(public_path().'/', '', $file);
        }

        return '';
    }
}
