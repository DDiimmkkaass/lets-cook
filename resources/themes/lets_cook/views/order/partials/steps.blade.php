<div class="order__steps order-steps">
    <div class="order-steps__num">
        <div class="order-steps__num-title">Шаг:</div>
        <div class="order-steps__num-count"><span data-step="1">1</span>/<span data-step="2">8</span></div>
    </div>

    <div class="order-steps__price">
        <div class="order-steps__price-title">Цена заказа:</div>
        <div class="order-steps__price-value">
            <span id="order_total_steps" data-price="1">{!! $basket->getPriceInOrder($recipes_count) !!}</span>
            <span data-price="2">{!! $currency !!}</span>
        </div>
    </div>
</div>