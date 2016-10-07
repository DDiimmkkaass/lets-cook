<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:33
 */

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\Order;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewOrder
 * @package App\Events\Frontend
 */
class NewOrder extends Event
{
    
    use SerializesModels;
    
    /**
     * @var \App\Models\Order
     */
    public $order;
    
    /**
     * NewOrder constructor.
     *
     * @param \App\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}