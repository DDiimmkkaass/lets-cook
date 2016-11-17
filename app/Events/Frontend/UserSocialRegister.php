<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:33
 */

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserSocialRegister
 * @package App\Events\Frontend
 */
class UserSocialRegister extends Event
{
    
    use SerializesModels;
    
    /**
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * @var array
     */
    public $input;
    
    /**
     * UserSocialRegister constructor.
     *
     * @param \App\Models\User $user
     * @param array            $input
     */
    public function __construct(User $user, $input)
    {
        $this->user = $user;
        $this->input = $input;
    }
}