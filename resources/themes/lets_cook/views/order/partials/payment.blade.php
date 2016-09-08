<section class="order__payment order-payment">
    <h2 class="order-payment__title georgia-title">Оплата</h2>

    <ul class="order-payment__list">
        @foreach($payment_methods as $id => $payment_method)
            <li class="order-payment__item transparent-large-button">
                <input type="radio" id="f-order-payment-{!! $id !!}" name="payment_method" value="{!! $id !!}">
                <label for="f-order-payment-{!! $id !!}">
                    <span data-device="mobile">{!! $payment_method !!}</span>
                    <span data-device="desktop">{!! $payment_method !!}</span>
                </label>
            </li>
        @endforeach
    </ul>
</section>