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

/**
 * Class EditableOrder
 * @package App\Http\Middleware
 */
class EditableOrder
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
     *
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
     * @param string                    $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = 'user')
    {
        $order = Order::find($request->route('order', 0));
        
        if ($order && $order->editable($permissions)) {
            return $next($request);
        }
        
        if ($request->ajax()) {
            return $this->response->make('Method Not Allowed', 405);
        }
        
        FlashMessages::add('warning', trans('messages.order has un editable status error'));
        
        return $this->response->redirectToRoute('admin.order.index');
    }
}
