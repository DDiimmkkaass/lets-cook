<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.08.16
 * Time: 11:54
 */

namespace App\Widgets\Subscribe;

class SubscribeWidget
{
    
    /**
     * @var string
     */
    protected $view = 'index';
    
    /**
     * @param string|null $template
     *
     * @return string
     */
    public function index($template = null)
    {
        if (view()->exists('widgets.subscribe.templates.'.$template.'.index')) {
            $this->view = 'templates.'.$template.'.index';
        }
        
        return view('widgets.subscribe.'.$this->view)->render();
    }
}