<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Subscribe\SubscribeRequest;
use App\Models\Subscribe;
use Exception;

/**
 * Class SubscribeController
 * @package App\Http\Controllers\Frontend
 */
class SubscribeController extends FrontendController
{
    /**
     * @var string
     */
    public $module = 'subscribe';
    
    /**
     * @param SubscribeRequest $request
     *
     * @return array
     */
    public function store(SubscribeRequest $request)
    {
        try {
            Subscribe::firstOrCreate(['email' => $request->get('email')]);
            
            return ['status' => 'success', 'message' => trans('front_messages.thanks for your subscribe')];
        } catch (Exception $e) {
            return ['status'  => 'error',
                    'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}