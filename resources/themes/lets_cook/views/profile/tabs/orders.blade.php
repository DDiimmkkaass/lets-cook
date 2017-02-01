<ul class="profile-orders-own__list">
    @if ($active_orders->count())
        @foreach($active_orders as $order)
            @if ($order->main_basket)
                <li class="profile-orders-own__item own-order">
                    <a href="{!! localize_route('order.edit', $order->id) !!}" class="own-order__change-link">
                        <div class="own-order__image"
                             style="background-image: url({!! $order->main_basket->getImage() !!});"></div>

                        <div class="own-order__info">
                            <div class="own-order__title">{!! $order->main_basket->getName() !!}</div>

                            <ul class="own-order__count-list">
                                <li class="own-order__count-item">
                                    <span>{!! $order->recipes->count() !!}</span>
                                    <span>@choice('front_labels.count_of_dinners', $order->recipes->count())</span>
                                </li>

                                <li class="own-order__count-item">
                                    <span>{!! $order->getPortions() !!}</span>
                                    <span>@choice('front_labels.count_of_portions', $order->getPortions())</span>
                                </li>
                            </ul>

                            <div class="own-order__when">{!! $order->getFormattedDeliveryDate() !!}</div>

                            <div class="own-order__price">{!! $order->total !!} {!! $currency !!}</div>

                            <div class="own-order__when own-order__status">{!! order_front_status($order) !!}</div>

                            <div href="#" class="own-order__change-button green-long-button">
                                Изменить
                            </div>

                            @if ($order->isStatus('changed'))
                                <div data-order_id="{!! $order->id !!}"
                                     data-token="{!! csrf_token() !!}"
                                     class="own-order__cancel-button">
                                    Отменить заказ
                                </div>
                            @endif

                            <div class="h-clearfix"></div>
                        </div>

                        @if ($order->additional_baskets->count())
                            <ul class="own-order__more-baskets-list">
                                @foreach($order->additional_baskets as $basket)
                                    <li class="own-order__more-baskets-item">
                                        <div class="own-order__more-baskets-img"
                                             style="background-image: url({!! $basket->getImage() !!});"></div>
                                        <div class="own-order__more-baskets-title">{!! $basket->getName() !!}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    @endif

    <li class="profile-orders-own__item new-order">
        <a href="{!! localize_route('baskets.index', 'current') !!}" class="new-order__link">
            <div class="new-order__icon">+</div>

            <div class="new-order__title">Выбрать корзину</div>
        </a>
    </li>
</ul>