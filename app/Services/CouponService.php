<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.16
 * Time: 1:01
 */

namespace App\Services;

use App\Models\Coupon;
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
            ->removeColumn('expired_at')
            ->make();
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
}