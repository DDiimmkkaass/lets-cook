<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 28.03.16
 * Time: 9:51
 */

namespace App\Services;

use App\Http\Requests\Frontend\User\UserUpdateRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\UserInfo;
use Datatables;
use DB;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    
    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getUserById($id)
    {
        return User::with('fields')->whereId($id)->first();
    }
    
    /**
     * @param UserUpdateRequest $request
     *
     * @return array
     */
    public function prepareInput(UserUpdateRequest $request)
    {
        $input = $request->all();
        
        return $input;
    }
    
    /**
     * @param \App\Models\User $model
     * @param array            $input
     */
    public function update(User $model, $input = [])
    {
        $model->email = $input['email'];
        $model->save();
        
        $this->processUserInfo($model, $input);
    }
    
    /**
     * @param \App\Models\User $model
     * @param string           $password
     */
    public function updatePassword(User $model, $password)
    {
        $model->password = $password;
        
        $model->save();
    }
    
    /**
     * @param User  $model
     * @param array $input
     */
    public function processUserInfo($model, $input)
    {
        if ($model->info) {
            $model->info->fill($input);
            
            $model->info->save();
        } else {
            $info = new UserInfo();
            $info->fill($input);
            
            $model->info()->save($info);
        }
    }
    
    /**
     * @param int   $user_id
     * @param array $status
     * @param array $with
     *
     * @return int
     */
    public function getOrders($user_id, $status = ['archived'], $with = [])
    {
        $with = array_merge(['main_basket', 'additional_baskets'], $with);
        
        return Order::with($with)
            ->ofStatus($status)
            ->latest()
            ->where('user_id', $user_id)
            ->get();
    }
    
    /**
     * @param $user_id
     *
     * @return array|\Bllim\Datatables\json
     */
    public function ordersTable($user_id)
    {
        $list = Order::with(
            'user',
            'user.subscribe',
            'user.subscribe.basket',
            'main_basket',
            'additional_baskets',
            'ingredients',
            'coupon'
        )
            ->select(
                'orders.id',
                'orders.user_id',
                DB::raw('1 as baskets_list'),
                'orders.payment_method',
                'orders.total',
                'orders.status',
                'orders.coupon_id',
                'orders.delivery_date',
                'orders.delivery_time',
                'orders.comment'
            )
            ->where('orders.user_id', $user_id);
        
        $dataTables = Datatables::of($list)
            ->editColumn(
                'baskets_list',
                function ($model) {
                    return view('order.datatables.baskets_list', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'total',
                function ($model) {
                    return $model->total.'<br>'.
                        ($model->paymentMethod('cash') ?
                            '<div class="red">'.trans('labels.for_courier').'</div>' :
                            ''
                        );
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view('order.datatables.status_changer', ['model' => $model])->render();
                }
            )
            ->editColumn(
                'coupon_id',
                function ($model) {
                    return $model->getCouponCode();
                }
            )
            ->editColumn(
                'delivery_date',
                function ($model) {
                    $html = view(
                        'partials.datatables.humanized_date',
                        [
                            'date'      => $model->delivery_date,
                            'in_format' => 'd-m-Y',
                        ]
                    )->render();
                    
                    return '<div class="text-center">'.$html.' '.$model->delivery_time.'<div>';
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view('user.datatables.orders_control_buttons', ['model' => $model])->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('user')
            ->removeColumn('subscribe')
            ->removeColumn('basket')
            ->removeColumn('main_basket')
            ->removeColumn('additional_baskets')
            ->removeColumn('ingredients')
            ->removeColumn('user_id')
            ->removeColumn('coupon')
            ->removeColumn('delivery_time')
            ->removeColumn('payment_method');
        
        return $dataTables->make();
    }
}