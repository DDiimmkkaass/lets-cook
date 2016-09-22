@if ($history_orders->count())
    <ul class="profile-history__table subscribe-table">
        @foreach($history_orders as $order)
            <li class="subscribe-table__row">
                <div class="subscribe-table__basket">
                    <span>{!! $order->main_basket->weekly_menu_basket->getName() !!}</span>
                    @if ($order->additional_baskets->count())
                        <span>+
                            {!! $order->getAdditionalBasketsList() !!}
                        </span>
                    @endif
                </div>

                <div class="subscribe-table__when">
                    {!! $order->getFormattedDeliveryDate() !!}
                </div>

                <div class="subscribe-table__functional">
                    <div class="subscribe-table__change">
                        <a href="{!! localize_route('order.repeat', $order->id) !!}">Повторить</a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endif