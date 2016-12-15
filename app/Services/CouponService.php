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
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class CouponService
 * @package App\Services
 */
class CouponService
{
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function tableIndex(Request $request)
    {
        $list = Coupon::with('tags')
            ->select(
                'coupons.id',
                'coupons.name',
                DB::raw('1 as tags_list'),
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
        
        $this->_implodeFilters($list, $request);
        
        return $dataTables = Datatables::of($list)
            ->editColumn(
                'tags_list',
                function ($model) {
                    return $model->tagsList();
                }
            )
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
            ->removeColumn('tags')
            ->removeColumn('users_count')
            ->removeColumn('users_type')
            ->removeColumn('expired_at')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function tableUsing(Request $request)
    {
        $list = Coupon::joinOrders()
            ->with('tags')
            ->where('orders.status', Order::getStatusIdByName('archived'))
            ->select(
                'coupons.id',
                'coupons.name',
                DB::raw('1 as tags_list'),
                'coupons.code',
                'orders.user_id',
                'orders.full_name',
                DB::raw('orders.id as order_id'),
                DB::raw('orders.total as order_total'),
                DB::raw('(orders.subtotal - orders.total) as order_discount'),
                'orders.created_at'
            )
            ->groupBy('orders.id');
        
        $this->_implodeFilters($list, $request);
        
        return $dataTables = Datatables::of($list)
            ->editColumn(
                'name',
                function ($model) {
                    return '<a href="'.route('admin.coupon.edit', $model->id).'" 
                            title="'.trans('labels.go_to_coupon').' '.$model->name.'">
                            '.$model->name.' (#'.$model->id.')
                            </a>';
                }
            )
            ->editColumn(
                'tags_list',
                function ($model) {
                    return $model->tagsList();
                }
            )
            ->editColumn(
                'full_name',
                function ($model) {
                    return '<a href="'.route('admin.user.show', $model->user_id).'" 
                            title="'.trans('labels.go_to_user').' '.$model->full_name.'">
                            '.$model->full_name.' (#'.$model->user_id.')
                            </a>';
                }
            )
            ->editColumn(
                'order_id',
                function ($model) {
                    return '<a href="'.route('admin.order.edit', $model->order_id).'" 
                            title="'.trans('labels.go_to_order').' '.$model->order_id.'">
                            '.trans('labels.order').' (#'.$model->order_id.')
                            </a>';
                }
            )
            ->editColumn(
                'created_at',
                function ($model) {
                    $html = view('partials.datatables.humanized_date', ['date' => $model->created_at])->render();
                    
                    return '<div class="text-center">'.$html.'<div>';
                }
            )
            ->editColumn(
                'order_total',
                function ($model) {
                    return $model->order_total / 100;
                }
            )
            ->editColumn(
                'order_discount',
                function ($model) {
                    return $model->order_discount / 100;
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('tags')
            ->removeColumn('user_id')
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
            return trans('front_messages.you already added this coupon');
        }
        
        if ($coupon->key == 'invite_friend' && $coupon->user_id == $user->id) {
            return trans('front_messages.you can not use you own coupon');
        }
        
        if ($coupon->getExpiredAt() && $coupon->getExpiredAt() < Carbon::now()) {
            return trans('front_messages.time of this coupon out');
        }
        
        $user_orders = max($user->orders()->count(), (int) $user->old_site_orders_count);
        
        if ($coupon->getStringUsersType() == 'new' && $user_orders > 0) {
            return trans('front_messages.coupon available only for new users');
        }
        
        if ($coupon->getStringUsersType() == 'exists' && $user_orders == 0) {
            return trans('front_messages.coupon available only for exists users');
        }
        
        if ((int) $coupon->users_count > 0) {
            $added_to_users = UserCoupon::whereCouponId($coupon->id)->count();
            
            if ($added_to_users >= $coupon->users_count) {
                return trans('front_messages.this coupon already used');
            }
        }
        
        return true;
    }
    
    /**
     * @param int  $coupon_id
     * @param User $user
     *
     * @return bool
     */
    public function availableById($coupon_id, User $user)
    {
        $user_coupon = $user->coupons()->whereCouponId($coupon_id)->first();
        
        if (!$user_coupon) {
            return trans('front_messages.you do not have this coupon');
        }
        
        return $this->available($user_coupon->coupon, $user);
    }
    
    /**
     * @param string|Coupon    $coupon
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function available($coupon, User $user)
    {
        $coupon = $this->getCoupon($coupon);
        
        if (!$coupon) {
            return trans('front_messages.coupon not find');
        }
        
        $now = Carbon::now();
        
        if ($coupon->key == 'invite_friend' && $coupon->user_id == $user->id) {
            return trans('front_messages.you can not use you own coupon');
        }
        
        if ($coupon->getStartedAt() && $coupon->getStartedAt() > $now) {
            return trans('front_messages.this coupon has not yet started');
        }
        
        if ($coupon->getExpiredAt() && $coupon->getExpiredAt() < $now) {
            return trans('front_messages.time of this coupon out');
        }
        
        if ($coupon->getStringUsersType() == 'new') {
            if ($user->orders()->count() > 0 || (int) $user->old_site_orders_count > 0) {
                return trans('front_messages.coupon available only for new users');
            }
        }
        
        if ($coupon->getStringUsersType() == 'exists') {
            if ($user->orders()->count() == 0 && (int) $user->old_site_orders_count == 0) {
                return trans('front_messages.coupon available only for exists users');
            }
        }
        
        if ((int) $coupon->users_count > 0) {
            if ($user->exists) {
                $added_to_users = UserCoupon::whereCouponId($coupon->id)->where('user_id', '<>', $user->id)->count();
            } else {
                $added_to_users = UserCoupon::whereCouponId($coupon->id)->count();
            }
            
            if ($added_to_users >= $coupon->users_count) {
                return trans('front_messages.this coupon already used');
            }
        }
        
        if ($coupon->count > 0) {
            $user_order_count = $user->orders()->whereCouponId($coupon->id)->count();
            
            if ($user_order_count >= $coupon->count) {
                return trans('front_messages.this coupon already used');
            }
        }
        
        return true;
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
                'description'   => trans('front_texts.registration coupon description'),
                'key'           => 'register',
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
     * @param \App\Models\User $user
     *
     * @return \App\Models\Coupon
     */
    public function createInviteFriendCoupon(User $user)
    {
        return $this->create(
            [
                'user_id'       => $user->id,
                'type'          => Coupon::getTypeIdByName('all'),
                'name'          => trans('front_labels.invite_friend'),
                'description'   => trans('front_texts.invite friend coupon'),
                'key'           => 'invite_friend',
                'discount'      => (int) variable('invite_friend_discount'),
                'discount_type' => Coupon::getDiscountTypeIdByName('percentage'),
                'count'         => 1,
                'users_count'   => 0,
                'users_type'    => Coupon::getUsersTypeIdByName('new'),
                'started_at'    => Carbon::now()->format('d-m-Y'),
                'create_count'  => 1,
            ]
        );
    }
    
    /**
     * @param \App\Models\User $for_user
     * @param \App\Models\User $from_user
     * @param bool             $default
     */
    public function giveInviteFriendCompensationCoupon(User $for_user, User $from_user, $default = false)
    {
        $coupon = $this->create(
            [
                'user_id'       => $from_user->id,
                'type'          => Coupon::getTypeIdByName('all'),
                'name'          => trans('front_labels.invite_friend_compensation'),
                'description'   => trans('front_texts.invite friend compensation coupon'),
                'key'           => 'invite_friend_compensation',
                'discount'      => (int) variable('invite_friend_compensation'),
                'discount_type' => Coupon::getDiscountTypeIdByName('percentage'),
                'count'         => 1,
                'users_count'   => 1,
                'users_type'    => Coupon::getUsersTypeIdByName('exists'),
                'started_at'    => Carbon::now()->format('d-m-Y'),
                'create_count'  => 1,
            ]
        );
        
        $this->saveUserCoupon($for_user, $coupon, $default);
    }
    
    /**
     * @param int $level
     * @param int $orders
     * @param int $discount
     * @param int $count
     *
     * @return \App\Models\Coupon
     * @internal param string $coupon_key
     */
    public function createLoyaltyCoupon($level, $orders, $discount, $count)
    {
        $coupon = $this->create(
            [
                'type'          => Coupon::getTypeIdByName('all'),
                'name'          => trans('front_labels.loyalty_program'),
                'description'   => trans(
                    'front_texts.coupon_loyalty_program :percentage :orders',
                    ['percentage' => $discount, 'orders' => $orders]
                ),
                'key'           => 'loyalty_program_'.$level,
                'discount'      => (int) $discount,
                'discount_type' => Coupon::getDiscountTypeIdByName('percentage'),
                'count'         => (int) $count,
                'users_count'   => 1,
                'users_type'    => Coupon::getUsersTypeIdByName('exists'),
                'started_at'    => Carbon::now()->format('d-m-Y'),
                'create_count'  => 1,
            ]
        );
        
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
    
    /**
     * @param Builder $list
     * @param Request $request
     */
    private function _implodeFilters(&$list, $request)
    {
        $filters = $request->get('datatable_filters');
        
        if (count($filters)) {
            foreach ($filters as $filter => $value) {
                if ($value !== '' && $value !== 'null') {
                    switch ($filter) {
                        case 'tags':
                            $list->joinTags()->whereIn('tags.id', explode(',', $value));
                            break;
                        case 'date_from':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $value = Carbon::createFromFormat('d-m-Y', $value)->startOfDay()->format('Y-m-d H:i:s');
                                $list->where('orders.created_at', '>=', $value);
                            }
                            break;
                        case 'date_to':
                            if (preg_match('/^[\d]{2}-[\d]{2}-[\d]{4}$/', $value)) {
                                $value = Carbon::createFromFormat('d-m-Y', $value)->endOfDay()->format('Y-m-d H:i:s');
                                $list->where('orders.created_at', '<=', $value);
                            }
                            break;
                    }
                }
            }
        }
    }
}