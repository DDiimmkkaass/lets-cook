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
}