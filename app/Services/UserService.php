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
use Carbon;
use ImageUploader;

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
}