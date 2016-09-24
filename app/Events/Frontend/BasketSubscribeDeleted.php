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
 * Class BasketSubscribeDeleted
 * @package App\Events\Frontend
 */
class BasketSubscribeDeleted extends Event
{
    
    use SerializesModels;
    
    /**
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * BasketSubscribeDeleted constructor.
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}