<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.11.16
 * Time: 12:32
 */

namespace App\Widgets\LatsShares;

use App\Models\Share;
use Pingpong\Widget\Widget;

/**
 * Class LatsSharesWidget
 * @package App\Widgets\LatsShares
 */
class LatsSharesWidget extends  Widget
{
    
    /**
     * @var string
     */
    protected $view = 'default';
    
    /**
     * @param null $template
     * @param int  $count
     *
     * @return mixed
     */
    public function index($template = null, $count = 3)
    {
        $list = Share::visible()->positionSorted()->take($count)->get();
        
        if (view()->exists('widgets.last_shares.templates.'.$template.'.index')) {
            $this->view = $template;
        }
        
        $link = localize_route('blog.index').str_replace('##', '#', '#'.variable('shares_tag'));
        
        return view('widgets.last_shares.templates.'.$this->view.'.index')
            ->with('list', $list)
            ->with('link', $link)
            ->render();
    }
}