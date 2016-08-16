<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:38
 */

namespace App\Events\Backend;

use App\Events\Event;
use App\Models\Order;
use Illuminate\Queue\SerializesModels;

/**
 * Class TmplOrderSuccessfullyPaid
 * @package App\Events\Backend
 */
class TmplOrderSuccessfullyPaid extends Event
{

    use SerializesModels;

    /**
     * @var \App\Models\Order
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
