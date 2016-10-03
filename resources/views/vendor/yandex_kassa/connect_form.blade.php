<div id="payment_connect_form">
    <form action="{{yandex_kassa_form_action()}}" method="{{yandex_kassa_form_method()}}" class="form-horizontal">
        <input name="scid" type="hidden" value="{{yandex_kassa_sc_id()}}">
        <input name="shopId" type="hidden" value="{{yandex_kassa_shop_id()}}">
        <input name="orderNumber" type="hidden" value="card_{{ $order['id'] }}">
        <input name="paymentType" type="hidden" value="AC">
        <input name="sum" type="hidden" value="1">
        <input name="customerNumber" type="hidden" value="{!! $order['user_id'] !!}">
        <input name="cardConnect" type="hidden" value="1">

        <button id="pay" type="submit" class="btn btn-primary">{{trans('yandex_kassa::form.button.pay')}}</button>
    </form>
</div>