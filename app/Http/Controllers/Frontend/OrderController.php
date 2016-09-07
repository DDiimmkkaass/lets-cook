<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.09.16
 * Time: 15:31
 */

namespace App\Http\Controllers\Frontend;

/**
 * Class OrderController
 * @package App\Http\Controllers\Frontend
 */
class OrderController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'order';
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($basket_id)
    {
        return $this->render($this->module.'.index');
    }
}