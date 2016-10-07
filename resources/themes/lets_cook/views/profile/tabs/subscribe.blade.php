@if ($subscribe)
    <form action="{!! localize_route('profiles.basket_subscribes.update') !!}" method="post"
          class="basket-subscribes-form">
        {!! csrf_field() !!}

        <div class="profile-subscribe__header">
            <div class="profile-subscribe__drop">
                <div class="profile-subscribe__select-wrapper">
                    @if ($weekly_menu)
                        <div class="profile-subscribe__select order-select">
                            <select name="basket_id" id="profile-subscribe-name">
                                @foreach($weekly_menu->baskets as $basket)
                                    <option value="{!! $basket->basket_id !!}"
                                            @if ($basket->basket_id == $subscribe->basket_id) selected @endif>
                                        {!! $basket->getName() !!}
                                        ({!! $basket->portions !!} @choice('front_labels.count_of_portions', $basket->portions)
                                        {!! $basket->getPriceInOrder($subscribe->recipes) !!} {!! $currency !!})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="profile-subscribe__add">
                        <div class="profile-subscribe__add-button">
                            <span data-icon>+</span>
                            <span data-device="mobile">Дополнительно</span>
                            <span data-device="desktop">доп.</span>
                        </div>

                        @if ($additional_baskets->count())
                            <div class="profile-subscribe__add-wrapper">
                                <div class="profile-subscribe__add-close"></div>

                                <ul class="profile-subscribe__add-list">
                                    @foreach($additional_baskets as $key => $basket)
                                        <li class="profile-subscribe__add-item square-red-checkbox">
                                            <input type="checkbox" id="order-more-{!! $key !!}"
                                                   name="baskets[{!! $key !!}]"
                                                   @if ($subscribe->additional_baskets->contains($basket->id))
                                                   checked
                                                   @endif
                                                   value="{!! $basket->id !!}">
                                            <label for="order-more-{!! $key !!}">
                                                {!! $basket->getName() !!}
                                                ({!! $basket->price !!} {!! $currency !!})
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="profile-subscribe__delivery order-delivery">
                <div class="order-delivery__day">
                    <div class="order-delivery__day-radio order-delivery-radio">
                        <input type="radio" id="order-subs-del-radio-1-0" name="delivery_date" value="0"
                               @if ($subscribe->delivery_date == 0) checked @endif>
                        <label for="order-subs-del-radio-1-0">Воскресенье</label>
                    </div>

                    <div class="order-delivery__day-radio order-delivery-radio">
                        <input type="radio" id="order-subs-del-radio-1-1" name="delivery_date" value="1"
                               @if ($subscribe->delivery_date == 1) checked @endif>
                        <label for="order-subs-del-radio-1-1">Понедельник</label>
                    </div>
                </div>

                <div class="order-delivery__time">
                    @foreach(config('order.delivery_times') as $key => $time)
                        <div class="order-delivery__time-radio order-delivery-radio">
                            <input type="radio" id="order-subs-del-radio-{!! $key !!}" name="delivery_time"
                                   @if ($time == $subscribe->delivery_time) checked @endif
                                   value="{!! $time !!}">
                            <label for="order-subs-del-radio-{!! $key !!}">{!! $time !!}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="profile-subscribe__subscribe">
                @foreach($subscribe_periods as $key => $period)
                    <div class="profile-subscribe__subscribe-checkbox square-red-checkbox">
                        <input type="checkbox" id="order-subscribe-{!! $key !!}" name="subscribe_period"
                               @if ($key == $subscribe->subscribe_period) checked @endif
                               value="{!! $key !!}">
                        <label for="order-subscribe-{!! $key !!}">{!! $period !!}</label>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($tmpl_orders->count())
            <ul class="profile-subscribe__table subscribe-table">
                @foreach($tmpl_orders as $order)
                    <li class="subscribe-table__row">
                        <div class="subscribe-table__basket">
                            <span>
                                {!! $order->main_basket ? $order->main_basket->getName() : $subscribe->basket->getName() !!}
                            </span>
                            @if ($order->additional_baskets->count())
                                <span>+ {!! $order->getAdditionalBasketsList() !!}</span>
                            @endif
                        </div>

                        <div class="subscribe-table__when">
                            {!! $order->getFormattedDeliveryDate() !!}
                        </div>

                        <div class="subscribe-table__functional">
                            @if ($order->main_basket)
                                <a href="{!! localize_route('order.edit', $order->id) !!}"
                                   class="subscribe-table__change h-margin-right-20">
                                    Изменить
                                </a>
                            @endif

                            <div title="Отменить заказ на эту неделю" class="subscribe-table__delete delete-tmpl-order"
                                 data-order_id="{!! $order->id !!}"
                                 data-token="{!! csrf_token() !!}">
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="profile-subscribe__submit">
                <div data-show="Показать все"
                     data-hide="Скрыть"
                     class="profile-subscribe__submit-wrapper h-subscribes-form-show-all">
                    Показать все
                </div>
            </div>
        @endif

        <div class="profile-subscribe__submit">
            <div class="profile-subscribe__submit-wrapper basket-subscribes-form-submit">Сохранить</div>
            <div class="profile-subscribe__submit-wrapper h-button-danger basket-subscribes-unsubscribe"
                 data-href="{!! localize_route('profiles.basket_subscribes.delete') !!}">
                Отписаться
            </div>
        </div>
    </form>
@endif