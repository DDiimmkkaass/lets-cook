@if ($history_orders->count())
    <div class="profile-orders-content__prev-orders">
        <div class="profile-orders-content__tabs-title" data-tab="prev-orders">Предыдущие заказы</div>

        <div class="profile-orders-content__main profile-orders-own" data-tab="prev-orders">
            <ul class="profile-orders-own__list">
                @foreach($history_orders as $key => $order)
                    <li class="profile-orders-own__item own-order" data-order="even">
                        <a href="{!! localize_route('order.repeat', $order->id) !!}" class="own-order__change-link">
                            <div class="own-order__image"
                                 style="background-image: url({!! $order->main_basket->getImage() !!});">
                                <div class="own-order__retry"><span>Повторить</span></div>
                            </div>

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
                            </div>
                        </a>
                    </li>
                    @break($key >= 4)
                @endforeach
            </ul>
        </div>
    </div>
@endif