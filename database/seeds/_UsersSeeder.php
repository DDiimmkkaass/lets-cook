<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class _UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        User::destroy(Group::clients()->first()->users()->get()->pluck('id')->toArray());
        DB::statement('ALTER TABLE `users` AUTO_INCREMENT='.(User::all()->count() + 1));
        
        for ($i = 0; $i < 10; $i++) {
            $input = [
                'email'    => $faker->email,
                'password' => 'admin',
            ];
            
            Sentry::register($input);
            
            $user = Sentry::getUserProvider()->findByLogin($input['email']);
            $user->activated = true;
            $user->save();
            
            $user_info = [
                'user_id'   => $user->id,
                'full_name' => $faker->name,
                'phone'     => $faker->phoneNumber,
                'gender'    => UserInfo::$genders[rand(0, 1)],
                'birthday'  => $faker->date('d-m-Y'),
                'avatar'    => $faker->imageUrl(640, 640, 'animals'),
            ];
            
            $user_info = new UserInfo($user_info);
            $user->info()->save($user_info);
            
            $clientsGroup = Group::clients()->first();
            $user->addGroup($clientsGroup);
        }
    }
}
