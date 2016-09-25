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
        
        $user_orders = $user->orders()->count();
        
        if ($coupon->getStringUsersType() == 'new' && $user_orders > 0) {
            return false;
        }
        
        if ($coupon->getStringUsersType() == 'exists' && $user_orders == 0) {
            return false;
        }
        
        if ($this->used($coupon, $user)) {
            return false;
        }
        
        return true;
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
                $user_orders = $orders->where('user_id', $user->id)->count();
                
                if ($user_orders) {
                    if ($coupon->count > 0) {
                        if ($user_orders >= $coupon->count) {
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
     * @param string|Coupon         $coupon
     * @param \App\Models\User|null $user
     *
     * @return bool
     */
    public function available(Coupon $coupon, $user = null)
    {
        $coupon = $this->getCoupon($coupon);
        
        if (!$coupon) {
            return false;
        }
        
        $now = Carbon::now();
        
        if ($coupon->getStartedAt() > $now || $this->used($coupon, $user)) {
            return false;
        }
        
        return true;
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
}