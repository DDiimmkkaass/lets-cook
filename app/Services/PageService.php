<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.02.16
 * Time: 22:22
 */

namespace App\Services;

use App\Models\Page;

/**
 * Class PageService
 * @package App\Services
 */
class PageService
{

    protected $module = 'page';

    /**
     * @param \App\Models\Page $model
     */
    public function setExternalUrl(Page $model)
    {
        $model->external_url = get_hashed_url($model, $this->module);

        $model->save();
    }

    /**
     * @param \App\Models\Page $model
     *
     * @return string
     */
    public function getPageTemplate(Page $model)
    {
        $template = str_replace('-', '_', $model->slug);

        return $this->module.'.'.
            (view()->exists($this->module.'.'.$template) ? $template : 'index');
    }
}