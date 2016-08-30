<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.08.16
 * Time: 18:25
 */

namespace App\Http\Controllers\Frontend;

/**
 * Class BasketController
 * @package App\Http\Controllers\Frontend
 */
class BasketController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'basket';
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return $this->render($this->module.'.index');
    }
    
    /**
     * @param int $basket_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($basket_id)
    {
        return $this->render($this->module.'.show');
    }
}