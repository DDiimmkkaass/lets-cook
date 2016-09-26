<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.07.15
 * Time: 12:31
 */

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Sentry;

/**
 * Class UserEditableOrder
 * @package App\Http\Middleware
 */
class UserEditableOrder
{
    
    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;
    
    /**
     * Create a new filter instance.
     *
     * @param  ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $error = false;
        
        $order = Order::find($request->route('order_id', 0));
        $now = active_week();
        
        if (!$order) {
            $error = true;
            $message = trans('front_messages.order not find');
        } elseif ($order->user_id != Sentry::getUser()->getId()) {
            $error = true;
            $message = trans('front_messages.you can not edit other users orders');
        } elseif (!$order->editable('user')) {
            $error = true;
            $message = trans('front_messages.order has un editable status');
        } elseif ($order->paymentMethod('online') && $order->isStatus('paid')) {
            $error = true;
            $message = trans('front_messages.you can not edit already paid online orders');
        } elseif (after_finalisation($now->year, $now->weekOfYear)) {
            if ($order->forCurrentWeek()) {
                $error = true;
                $message = trans('front_messages.you can not edit this order any more');
            } else {
                $dt = active_week();
                
                $to = $dt->endOfDay()->format('d-m-Y');
                $from = $dt->subDay()->startOfDay()->format('d-m-Y');
                
                $delivery_date = $request->get('delivery_date', null);
                
                if ($delivery_date && ($delivery_date >= $from && $delivery_date <= $to)) {
                    $error = true;
                    $message = trans('front_messages.the orders for this week are no longer accepted');
                }
            }
        }
        
        if ($error) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->response->json(['message' => $message], 405);
            }
            
            FlashMessages::add('error', $message);
            
            return $this->response->redirectToRoute('profiles.orders.index');
        }
        
        return $next($request);
    }
}
