<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ImageUploader
 * @package App\Facades
 */
class ImageUploader extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'image_uploader';
    }
}