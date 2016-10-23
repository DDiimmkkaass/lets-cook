<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.16
 * Time: 1:01
 */

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Datatables;

/**
 * Class CouponService
 * @package App\Services
 */
class CouponService
{
    
    /**
     * @return array|\Bllim\Datatables\json
     */
    public function table()
    {
        $list = Coupon::select(
            'coupons.id',
            'coupons.name',
            'coupons.code',
            'coupons.discount',
            'coupons.discount_type',
            'coupons.type',
            'coupons.count',
            'coupons.users_count',
            'coupons.users_type',
            'coupons.started_at',
            'coupons.expired_at'
        );
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'coupons.id', '=', '$1')
            ->filterColumn('name', 'where', 'coupons.name', 'LIKE', '%$1%')
            ->filterColumn('code', 'where', 'coupons.code', 'LIKE', '%$1%')
            ->editColumn(
                'discount_type',
                function ($model) {
                    return trans('labels.discount_discount_type_'.$model->getStringDiscountType());
                }
            )
            ->editColumn(
                'count',
                function ($model) {
                    return view('coupon.datatables.parameters', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'type',
                function ($model) {
                    return trans('labels.discount_type_'.$model->getStringType());
                }
            )
            ->editColumn(
                'started_at',
                function ($model) {
                    return view('coupon.datatables.period', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view('partials.datatables.control_buttons', ['model' => $model, 'type' => 'coupon'])
                        ->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('users_count')
            ->removeColumn('users_type')
            ->removeColumn('expired_at')
            ->make();
    }
    
    /**
     * @param array $input
     *
     * @return \App\Models\Coupon
     */
    public function create($input)
    {
        if (empty($input['codes'])) {
            for ($i = 0; $i < $input['create_count']; $i++) {
                $model = new Coupon($input);
                $model->code = $this->newCode();
                
                $model->save();
            }
        } else {
            $codes = trim(strip_tags($input['codes']));
            $codes = preg_split('/\r\n|[\r\n]/', $codes);
            
            foreach ($codes as $code) {
                $model = new Coupon($input);
                $model->code = $code;
                
                $model->save();
            }
        }
        
        return $model;
    }
    
    /**
     * @return string
     */
    public function newCode()
    {
        do {
            $code = strtolower(str_random(config('coupons.code_length')));
            
            $exists = Coupon::whereCode($code)->count();
        } while ($exists);
        
        return $code;
    }
    
    /**
     * @param \App\Models\Coupon $coupon
     * @param \App\Models\User   $user
     *
     * @return bool
     */
    public function validToAdd(Coupon $coupon, User $user)
    {
        if ($user->coupons()->whereCouponId($coupon->id)->count()) {
            return false;
        }
        
        $user_orders = max($user->orders()->count(), (int) $user->old_site_orders_count);
        
        if ($coupon->getStringUsersType() == 'new' && $user_orders > 0) {
            return false;
        }
        
        if ($coupon->getStringUsersType() == 'exists' && $user_orders == 0) {
            return false;
        }
        
        if ($this->used($coupon, $user)) {
            return false;
        }
        
        if ((int) $coupon->users_count > 0) {
            $added_to_users = UserCoupon::whereCouponId($coupon->id)->count();
            
            if ($added_to_users >= $coupon->users_count) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param string|Coupon $coupon
     *
     * @return Coupon
     */
    public function getCoupon($coupon)
    {
        if (!($coupon instanceof Coupon)) {
            $coupon = Coupon::whereCode($coupon)->first();
        }
        
        return $coupon;
    }
    
    /**
     * @param string|Coupon         $coupon
     * @param \App\Models\User|null $user
     *
     * @return bool
     */
    public function available($coupon, $user = null)
    {
        $coupon = $this->getCoupon($coupon);
        
        if (!$coupon) {
            return false;
        }
        
        $now = Carbon::now();
        
        if ($coupon->getStringUsersType() == 'new') {
            if ($user && ($user->orders()->count() > 0 || (int) $user->old_site_orders_count > 0)) {
                return false;
            }
        }
        
        if ($coupon->getStringUsersType() == 'exists') {
            if (!$user || ($user->orders()->count() == 0 && (int) $user->old_site_orders_count == 0)) {
                return false;
            }
        }
        
        if ($coupon->getStartedAt() > $now || $this->used($coupon, $user)) {
            return false;
        }
        
        if ((int) $coupon->users_count > 0) {
            if ($user) {
                $added_to_users = UserCoupon::whereCouponId($coupon->id)->where('user_id', '<>', $user->id)->count();
            } else {
                $added_to_users = UserCoupon::whereCouponId($coupon->id)->count();
            }
            
            if ($added_to_users >= $coupon->users_count) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param int       $coupon_id
     * @param null|User $user
     *
     * @return bool
     */
    public function availableById($coupon_id, $user = null)
    {
        $coupon = Coupon::find($coupon_id);
        
        return $this->available($coupon, $user);
    }
    
    /**
     * @param string|Coupon         $coupon
     * @param \App\Models\User|null $user
     *
     * @return bool
     */
    public function used($coupon, $user = null)
    {
        $coupon = $this->getCoupon($coupon);
        
        if (!$coupon) {
            return true;
        }
        
        $now = Carbon::now();
        
        if ($coupon->getExpiredAt() && $coupon->getExpiredAt() < $now) {
            return true;
        }
        
        if ($coupon->getStringUsersType() == 'new') {
            $coupon->count = 1;
            $coupon->users_count = 1;
        }
        
        $orders = Order::notOfStatus('deleted')->whereCouponId($coupon->id)->get(['id', 'user_id', 'status']);
        
        if ($coupon->users_count > 0) {
            $users = $orders->groupBy('user_id')->count();
            
            if ($user) {
                $orders->where('user_id', $user->id)->all();
                
                if ($orders->count()) {
                    if ($coupon->count > 0) {
                        if ($orders->count() >= $coupon->count) {
                            return true;
                        }
                    }
                    
                    return false;
                }
            }
            
            if ($users >= $coupon->users_count) {
                return true;
            }
            
            return false;
        }
        
        if ($coupon->count > 0 && $orders->count() >= $coupon->count) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param UserCoupon $coupon
     */
    public function makeDefault(UserCoupon $coupon)
    {
        UserCoupon::whereUserId($coupon->user_id)->update(['default' => false]);
        
        $coupon->default = true;
        $coupon->save();
    }
    
    /**
     * @param \App\Models\Order $order
     * @param int|float         $main_basket_price
     * @param int|float         $additional_baskets_price
     *
     * @return int|float
     */
    public function calculateOrderTotal(Order $order, $main_basket_price, $additional_baskets_price)
    {
        $coupon = Coupon::find($order->coupon_id);
        
        if ($coupon) {
            $type = $coupon->getStringType();
            $discount_type = $coupon->getStringDiscountType();
            
            if ($type == 'all' || $type == 'main') {
                $main_basket_price -= ($discount_type == 'absolute') ?
                    $coupon->discount :
                    $main_basket_price / 100 * $coupon->discount;
                
                $main_basket_price = $main_basket_price < 0 ? 0 : $main_basket_price;
            }
            
            if ($type == 'all' || $type == 'additional') {
                $additional_baskets_price -= ($discount_type == 'absolute') ?
                    $coupon->discount :
                    $additional_baskets_price / 100 * $coupon->discount;
                
                $additional_baskets_price = $additional_baskets_price < 0 ? 0 : $additional_baskets_price;
            }
        }
        
        return round($main_basket_price + $additional_baskets_price);
    }
    
    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Coupon
     */
    public function giveRegistrationCoupon(User $user)
    {
        $coupon = $this->create(
            [
                'type'          => Coupon::getTypeIdByName('all'),
                'name'          => trans('front_labels.registration_coupon'),
                'description'   => trans('front_texts.registration_coupon_description'),
                'discount'      => (int) variable('registration_coupon_discount'),
                'discount_type' => Coupon::getDiscountTypeIdByName('percentage'),
                'count'         => 1,
                'users_count'   => 1,
                'users_type'    => Coupon::getUsersTypeIdByName('new'),
                'started_at'    => Carbon::now()->format('d-m-Y'),
                'create_count'  => 1,
            ]
        );
        
        $this->saveUserCoupon($user, $coupon, true);
        
        return $coupon;
    }
    
    /**
     * @param User   $user
     * @param Coupon $coupon
     * @param bool   $default
     */
    public function saveUserCoupon($user, $coupon, $default = false)
    {
        $coupon = $this->getCoupon($coupon);
        
        if (!UserCoupon::whereUserId($user->id)->whereCouponId($coupon->id)->count()) {
            UserCoupon::create(
                [
                    'user_id'   => $user->id,
                    'coupon_id' => $coupon->id,
                    'default'   => $default,
                ]
            );
        }
    }
}