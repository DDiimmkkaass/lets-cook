<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.08.15
 * Time: 15:16
 */

namespace App\Widgets\Banner;

use App\Models\Banner;
use Pingpong\Widget\Widget;

/**
 * Class BannerWidget
 * @package App\Widgets\Banner
 */
class BannerWidget extends Widget
{

    /**
     * @var string
     */
    protected $view = 'default';
    
    /**
     * @param string $position
     * @param array  $data
     *
     * @return $this
     */
    public function index($position, $data = [])
    {
        $banners = [];

        $list = Banner::with(['translations', 'visible_items', 'visible_items.translations'])
            ->whereLayoutPosition($position)
            ->visible()
            ->get();

        if (count($list)) {
            foreach ($list as $banner) {
                if (view()->exists('widgets.banner.templates.'.$banner->template.'.index')) {
                    $this->view = $banner->template;
                }

                $banners[] = view('widgets.banner.templates.'.$this->view.'.index')
                    ->with(['banner' => $banner])
                    ->with($data)
                    ->render();
            }

            return view('widgets.banner.index')->with('banners', $banners)->render();
        }
    }
}