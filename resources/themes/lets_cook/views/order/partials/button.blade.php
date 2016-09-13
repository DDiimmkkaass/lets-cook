<section class="order__submit order-submit">
    <div class="order-submit__wrapper">
        <div class="order-submit__left">
            <div class="order-submit__promocode order-promocode">
                <h3 class="order-promocode__title">
                    <span data-device="mobile">Промокод</span>
                    <span data-device="desktop">
                        Промокод можно получить в купонных сервисах или следите за акция на нашем сайте.</span>
                </h3>

                <div class="order-promocode__inputs">
                    <input type="text" class="input-text-large" name="order-promocode__text"
                           placeholder="Промокод">
                    <input type="button" name="order-promocode__submit" value="Пересчитать">
                </div>
            </div>

            <div id="order_total_mobile" class="order-submit__price georgia-title" data-device="mobile">
                {!! $basket->getPriceInOrder() !!}<span>{!! $currency !!}</span>
            </div>
        </div>

        <div class="order-submit__right">
            <div id="order_total_desktop" data-total="{!! $basket->getPriceInOrder() !!}" class="order-submit__price" data-device="desktop">
                {!! $basket->getPriceInOrder() !!}<span>{!! $currency !!}</span>
            </div>

            <button type="submit" name="order-submit">
                <span data-device="mobile">Заказать</span>
                <span data-device="desktop">Оформить заказ</span>
            </button>
        </div>
    </div>
</section>