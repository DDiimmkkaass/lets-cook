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
    private $cached_img = null;

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
    public function create($path)
    {
        if (strpos($path, public_path()) === false) {
            $path = public_path($path);
        }

        $ins = new static;
        
        if (File::exists($path)) {
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
     * @return \App\Classes\Thumb|string
     *
     * Quick method to resize image
     */
    public function thumb($path, $w, $h)
    {
        if ($this->cached($path, $w, $h)) {
            return $this;
        }
    
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
    public function square($path, $s)
    {
        return $this->thumb($path, $s, $s);
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
        if ($this->cached_img) {
            return $this->cached_img;
        }
        
        if ($this->img) {
            $file = $this->getNewFilePath();
            
            if (File::exists(public_path($file))) {
                return $file;
            } else {
                return $this->save($file);
            }
        }

        return '';
    }
    
    /**
     * @param string|null $file
     * @return bool|string Save modified image
     *
     * Save modified image
     */
    public function save($file = null)
    {
        if ($this->img) {
            $file = $file ? $file : $this->getNewFilePath();
            
            if ($this->img->save($file)) {
                return $file;
            }
        }

        return false;
    }
    
    /**
     * @param $path
     * @param $w
     * @param $h
     *
     * @return bool
     */
    public function cached($path, $w, $h)
    {
        if (strpos($path, public_path()) === false) {
            $path = public_path($path);
        }
        
        $path_info = pathinfo($path);
        
        
        $file_name = md5($path);
        $path = substr($file_name, 0, 2).'/'.substr($file_name, 2, 2);
        
        $path = 'thumbs/'.$path.'/'.$file_name."_{$w}x{$h}".'.'.$path_info['extension'];
        
        if (File::exists(public_path($path))) {
            $this->cached_img = $path;
            
            return true;
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
            

            $file_name = md5($this->oldPath);
            $path = substr($file_name, 0, 2).'/'.substr($file_name, 2, 2);

            $path = 'thumbs/'.$path;

            if (!File::exists(public_path($path))) {
                @File::makeDirectory(public_path($path), 0755, true);
            }

            return $path.'/'.$file_name.$this->postfix.'.'.$path_info['extension'];
        }

        return '';
    }
}
