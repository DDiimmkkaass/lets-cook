<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:33
 */

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\BasketSubscribe;
use Illuminate\Queue\SerializesModels;

/**
 * Class BasketSubscribeUpdated
 * @package App\Events\Frontend
 */
class BasketSubscribeUpdated extends Event
{
    
    use SerializesModels;
    
    /**
     * @var \App\Models\BasketSubscribe
     */
    public $subscribe;
    
    /**
     * BasketSubscribeUpdated constructor.
     *
     * @param \App\Models\BasketSubscribe $subscribe
     */
    public function __construct(BasketSubscribe $subscribe)
    {
        $this->subscribe = $subscribe;
    }
}