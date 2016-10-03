<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 21.09.16
 * Time: 15:05
 */

namespace App\Listeners\Events\Payments;

use App\Models\Card;
use Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;

/**
 * Class SaveUserCard
 * @package App\Listeners\Events\Payments
 */
class SaveUserCard
{
    
    /**
     * @param \Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse
     *
     * @return void
     */
    public function handle(BeforePaymentAvisoResponse $event)
    {
        if ($event->request->get('cardConnect', false)) {
            if ($event->request->isValidHash()) {
                $card_id = $event->request->get('orderNumber');
                $card_id = explode('_', $card_id);
                
                $card = Card::find($card_id[1]);
    
                $card->invoice_id = $event->request->get('invoiceId');
                $card->save();
            }
        }
    }
}