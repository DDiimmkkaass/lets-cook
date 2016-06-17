<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 28.03.16
 * Time: 9:51
 */

namespace App\Services;

use App\Http\Requests\Frontend\User\UserUpdateRequest;
use App\Models\Field;
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
        return User::with(['info', 'fields'])->whereId($id)->first();
    }

    /**
     * @param UserUpdateRequest $request
     *
     * @return array
     */
    public function prepareInput(UserUpdateRequest $request)
    {
        $input = $request->all();

        $input['avatar'] = $request->file('avatar_new') ?
            ImageUploader::upload($request->file('avatar_new'), 'user') :
            $input['avatar_old'];

        $input['birthday'] = Carbon::now()->format('d-m-Y');

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

        $this->processFields($model);
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
     * @param \App\Models\User $user
     */
    public function processFields(User $user)
    {
        $data = request('fields.remove', []);
        foreach ($data as $id) {
            $item = $user->fields()->find($id);

            if ($item) {
                $item->delete();
            }
        }

        $data = request('fields.old', []);
        foreach ($data as $key => $item) {
            if (!empty($item['value'])) {
                $_item = $user->fields()->find($key);

                if ($item) {
                    $_item->update($item);
                }
            }
        }

        $data = request('fields.new', []);
        foreach ($data as $item) {
            if (!empty($item['value'])) {
                $item = new Field($item);
                $user->fields()->save($item);
            }
        }
    }
}