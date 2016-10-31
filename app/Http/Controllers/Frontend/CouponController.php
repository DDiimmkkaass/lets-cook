<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\UserCoupon\CouponCheckRequest;
use App\Http\Requests\Frontend\UserCoupon\UserCouponCreateRequest;
use App\Http\Requests\Frontend\UserCoupon\UserCouponMakeDefaultRequest;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\CouponService;
use Exception;

/**
 * Class CouponController
 * @package App\Http\Controllers\Frontend
 */
class CouponController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'coupon';
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * CouponController constructor.
     *
     * @param \App\Services\CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        parent::__construct();
        
        $this->couponService = $couponService;
    }
    
    /**
     * @param \App\Http\Requests\Frontend\UserCoupon\UserCouponCreateRequest $request
     *
     * @return array
     */
    public function store(UserCouponCreateRequest $request)
    {
        try {
            $coupon = $this->couponService->getCoupon($request->get('code'));
            
            $status = $this->couponService->validToAdd($coupon, $this->user);
            
            if ($status !== true) {
                return [
                    'status'  => 'notice',
                    'message' => $status,
                ];
            }
            
            $model = UserCoupon::create(
                [
                    'user_id'   => $this->user->id,
                    'coupon_id' => $coupon->id,
                    'default'   => $coupon->started() ? true : false,
                ]
            );
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.coupon successfully added'),
                'html'    => view('profile.partials.coupon', ['coupon' => $model])->render(),
                'default' => (int) $model->default,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param \App\Http\Requests\Frontend\UserCoupon\UserCouponMakeDefaultRequest $request
     *
     * @return array
     */
    public function makeDefault(UserCouponMakeDefaultRequest $request)
    {
        try {
            $coupon = $this->user->coupons()->whereCouponId($request->get('coupon_id'))->first();
            
            if (!$coupon->available($this->user)) {
                return [
                    'status'  => 'error',
                    'message' => trans('front_messages.you cannot make this coupon default'),
                ];
            }
            
            if ($coupon->default) {
                return [
                    'status'  => 'notice',
                    'message' => trans('front_messages.coupon already default'),
                ];
            }
            
            $coupon->default = true;
            $coupon->save();
            
            return [
                'status'  => 'success',
                'message' => trans('front_messages.coupon successfully make default'),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param \App\Http\Requests\Frontend\UserCoupon\CouponCheckRequest $request
     *
     * @return array
     */
    public function check(CouponCheckRequest $request)
    {
        try {
            $coupon = $this->couponService->getCoupon($request->get('code'));
            
            $status = $this->couponService->available($coupon, $this->user ? $this->user : new User());
            
            if ($status !== true) {
                return [
                    'status'  => 'error',
                    'message' => $status,
                ];
            }
            
            $main_discount = in_array($coupon->getStringType(), ['all', 'main']) ? $coupon->discount : 0;
            $additional_discount = in_array($coupon->getStringType(), ['all', 'additional']) ? $coupon->discount : 0;
            
            return [
                'status'              => 'success',
                'main_discount'       => $main_discount,
                'additional_discount' => $additional_discount,
                'discount_type'       => $coupon->getStringDiscountType(),
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}