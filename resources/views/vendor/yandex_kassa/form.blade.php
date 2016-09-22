<div id="payment_form">
    <form action="{{yandex_kassa_form_action()}}" method="{{yandex_kassa_form_method()}}" class="form-horizontal">
        <input name="scid" type="hidden" value="{{yandex_kassa_sc_id()}}">
        <input name="shopId" type="hidden" value="{{yandex_kassa_shop_id()}}">
        <input name="orderNumber" type="hidden" value="{{ $order->id }}">
        <input name="paymentType" type="hidden" value="">
        <input name="sum" type="hidden" value="{!! $order->total !!}">
        <input name="customerNumber" type="hidden" value="{!! $order->user_id !!}">
        <input name="cps_phone" value="{!! $order->phone !!}" type="hidden"/>
        <input name="cps_email" value="{!! $order->email !!}" type="hidden"/>

        <button id="pay" type="submit" class="btn btn-primary">{{trans('yandex_kassa::form.button.pay')}}</button>
    </form>
</div>