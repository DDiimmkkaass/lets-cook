<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 28.09.16
 * Time: 14:20
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Card\CardCreateRequest;
use App\Http\Requests\Frontend\Card\CardUpdateRequest;
use App\Models\Card;
use App\Services\PaymentService;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class CardController
 * @package App\Http\Controllers\Frontend
 */
class CardController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'card';
    
    /**
     * @var \App\Services\PaymentService
     */
    private $paymentService;
    
    /**
     * CardController constructor.
     *
     * @param \App\Services\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->data('cards', Card::ofUser($this->user->id)->get());
        
        return $this->render($this->module.'.index');
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('card', new Card());
        
        return $this->render($this->module.'.create');
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Card\CardCreateRequest $request
     *
     * @return array
     */
    public function store(CardCreateRequest $request)
    {
        try {
            $card = new Card($request->only(['name', 'number', 'default']));
            $card->user_id = $this->user->id;
            
            $card->save();
    
            $provider = $this->paymentService->getProvider();
    
            $form = $provider->getConnectForm($card->toArray());
    
            return [
                'message' => trans('front_messages.card successfully saved'),
                'status' => 'success',
                'html'   => $form,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int $card_id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($card_id)
    {
        try {
            $this->data('card', Card::ofUser($this->user->id)->whereId($card_id)->firstOrFail());
            
            return $this->render($this->module.'.edit');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('front_messages.card not found'));
        }
        
        return redirect()->route('profiles.cards.index');
    }
    
    /**
     * @param int                                                $card_id
     * @param \App\Http\Requests\Frontend\Card\CardUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($card_id, CardUpdateRequest $request)
    {
        try {
            $card = Card::ofUser($this->user->id)->whereId($card_id)->firstOrFail();
            
            $card->fill($request->only(['name', 'number', 'default']));
            $card->save();
            
            FlashMessages::add('success', trans('front_messages.card successfully updated'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('front_messages.card not found'));
        }
        
        return redirect()->route('profiles.cards.index');
    }
    
    /**
     * @param int $card_id
     *
     * @return array
     */
    public function connect($card_id)
    {
        try {
            $card = Card::ofUser($this->user->id)->whereId($card_id)->firstOrFail();
            
            if ($card->invoice_id) {
                return [
                    'status' => 'notice',
                    'message'   => trans('front_messages.card already connected'),
                ];
            }
    
            $provider = $this->paymentService->getProvider();
    
            $form = $provider->getConnectForm($card);
            
            return [
                'status' => 'success',
                'html'   => $form,
            ];
        } catch (ModelNotFoundException $e) {
            $message = trans('front_messages.card not found');
        } catch (Exception $e) {
            $message = trans('front_messages.an error has occurred, please reload the page and try again');
        }
        
        return [
            'status'  => 'error',
            'message' => $message,
        ];
    }
    
    /**
     * @param int $card_id
     *
     * @return array
     */
    public function delete($card_id)
    {
        try {
            $card = Card::ofUser($this->user->id)->whereId($card_id)->firstOrFail();
            
            if ($card->default) {
                $status = 'notice';
                $message = trans('front_messages.you cannot delete a default card');
            } else {
                $card->delete();
                
                $status = 'success';
                $message = trans('front_messages.card successfully deleted');
            }
            
            return [
                'status'  => $status,
                'message' => $message,
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.card not found'),
            ];
        }
    }
}