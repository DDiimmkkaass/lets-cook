<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.07.15
 * Time: 12:31
 */

namespace App\Http\Middleware;

use App\Services\OrderService;
use Carbon;
use Closure;

/**
 * Class OrderBeforeFinalisationDate
 * @package App\Http\Middleware
 */
class OrderBeforeFinalisationDate
{
    /**
     * @var \App\Services\OrderService
     */
    private $orderService;
    
    /**
     * OrderBeforeFinalisationDate constructor.
     *
     * @param \App\Services\OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
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
        $order = $this->orderService->getOrder($request->route('order_id'));
        
        $now = Carbon::now()->addWeek();
        
        if (after_finalisation($now->year, $now->weekOfYear)) {
            if ($order->forCurrentWeek()) {
                return response()->json(
                    [
                        'status'  => 'error',
                        'message' => trans('front_messages.you can not edit this order any more'),
                    ]
                );
            }
    
            $dt = active_week();
    
            $to = $dt->endOfDay()->format('d-m-Y');
            $from = $dt->subDay()->startOfDay()->format('d-m-Y');
    
            $delivery_date = $request->get('delivery_date', null);
            
            if ($delivery_date && ($delivery_date >= $from && $delivery_date <= $to)) {
                return response()->json(
                    [
                        'status'  => 'error',
                        'message' => trans('front_messages.the orders for this week are no longer accepted'),
                    ]
                );
            }
        }
        
        return $next($request);
    }
}
