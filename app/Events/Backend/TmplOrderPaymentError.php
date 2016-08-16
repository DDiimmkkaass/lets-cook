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
 * Class TmplOrderPaymentError
 * @package App\Events\Backend
 */
class TmplOrderPaymentError extends Event
{

    use SerializesModels;

    /**
     * @var \App\Models\Order
     */
    public $order;
    
    
    /**
     * @var string
     */
    public $message;
    
    /**
     * Create a new event instance.
     *
     * @param \App\Models\Order $order
     * @param string            $message
     */
    public function __construct(Order $order, $message = '')
    {
        $this->order = $order;
        $this->message = $message;
    }
}
