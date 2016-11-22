<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Events\Backend;

use App\Events\Event;
use App\Models\Order;
use Illuminate\Queue\SerializesModels;

/**
 * Class CompletedOrderArchived
 * @package App\Events\Backend
 */
class CompletedOrderArchived extends Event
{
    
    use SerializesModels;
    
    /**
     * @var Order
     */
    public $order;
    
    /**
     * Create a new event instance.
     *
     * @param \App\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
