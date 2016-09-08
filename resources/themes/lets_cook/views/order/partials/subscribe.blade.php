<section class="order__subscribe order-subscribe">
    <h2 class="order-subscribe__title">
        <span class="georgia-title" data-device="mobile">Подписка</span>
        <span data-device="desktop">Хотите получать эту корзину регулярно?</span>
    </h2>

    <ul class="order-subscribe__list">
        @foreach($subscribe_periods as $period => $label)
            <li class="order-subscribe__item transparent-large-button">
                <input type="radio" id="f-order-subscribe-{!! $period !!}"
                       name="subscribe_period" value="{!! $period !!}">
                <label for="f-order-subscribe-{!! $period !!}">
                    <span data-device="mobile">{!! $label !!}</span>
                    <span data-device="desktop">{!! $label !!}</span>
                </label>
            </li>
        @endforeach
    </ul>

    <a href="{!! localize_url('/pages/podpiska') !!}" target="_blank" class="order-subscribe__terms">Подробнее об условиях</a>
</section>