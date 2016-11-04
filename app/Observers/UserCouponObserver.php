<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 24.08.16
 * Time: 15:14
 */

namespace App\Observers;

use App\Models\UserCoupon;

/**
 * Class UserCouponObserver
 * @package App\Observers
 */
class UserCouponObserver
{
    /**
     * @param \App\Models\UserCoupon $model
     */
    public function saved(UserCoupon $model)
    {
        if ($model->default) {
            UserCoupon::ofUser($model->user_id)
                ->whereDefault(true)
                ->where('id', '<>', $model->id)
                ->update(['default' => false]);
        }
    }
}