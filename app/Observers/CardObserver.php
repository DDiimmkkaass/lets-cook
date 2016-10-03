<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Observers;

use App\Models\Card;

/**
 * Class CardObserver
 * @package App\Observers
 */
class CardObserver
{
    /**
     * @param \App\Models\Card $model
     */
    public function saved(Card $model)
    {
        if ($model->default) {
            Card::whereDefault(true)->where('id', '<>', $model->id)->update(['default' => false]);
        }
    }
}