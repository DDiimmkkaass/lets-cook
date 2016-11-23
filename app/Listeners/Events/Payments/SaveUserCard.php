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
        $rebilling = $event->request->get('rebillingOn');
        $baseInvoiceId = $event->request->get('baseInvoiceId');
        
        if ($rebilling == 'true' && !$baseInvoiceId) {
            if ($event->request->isValidHash()) {
                $order_number = $event->request->get('orderNumber');
                
                if (strpos($order_number, 'card_') !== false) {
                    $card_id = explode('_', $order_number);
                    
                    $card = Card::find($card_id[1]);
                } else {
                    $card = new Card(
                        [
                            'name'    => trans('front_labels.main_card').' ('.
                                trans('front_labels.order').' #'.$order_number.')',
                            'default' => true,
                        ]
                    );
                    $card->user_id = $event->request->get('customerNumber');
                }
    
                $card->invoice_id = $event->request->get('invoiceId');
                $card->save();
            }
        }
    }
}