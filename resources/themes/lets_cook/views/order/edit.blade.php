@extends('profile.layouts.orders')

@section('__content')

    <div class="profile-orders-content__main order-edit" data-tab="my-orders-edit">
        <form action="{!! localize_route('order.update', $order->id) !!}" method="post" class="order-edit-form">
            {!! csrf_field() !!}
            @php($recipes_count = $order->recipes->count())
            <input type="hidden" name="recipes_count" id="recipes_count" value="{!! $recipes_count !!}">

            <div class="order-edit__wrapper">
                <div class="order-edit__left">
                    <div class="order-edit__main">
                        <div class="order-edit__img"
                             style="background-image: url({!! $order->main_basket->getImage() !!});">
                            <h2 class="order-edit__title">{!! $order->main_basket->getName() !!}</h2>
                            <div class="order-edit__price">{!! $order->main_basket->price !!} {!! $currency !!}</div>
                        </div>

                        <div class="order-edit__desc">
                            {!! $order->main_basket->getDescription() !!}
                        </div>
                    </div>

                    @include('order.partials.edit_additional_baskets')
                </div>

                <div class="order-edit__right">
                    <div class="order-edit__select order-select @if (after_week_closing($weekly_menu->year, $weekly_menu->week)) h-hidden @endif">
                        <select name="basket_id" id="order-select-name">
                            @foreach($weekly_menu->baskets()->get() as $basket)
                                <option value="{!! $basket->id !!}"
                                        data-price="{!! $basket->getPriceInOrder($recipes_count) !!}"
                                        @if ($basket->id == $order->main_basket->weekly_menu_basket_id) selected @endif>
                                    {!! $basket->getName() !!}
                                    ({!! $basket->getPriceInOrder($recipes_count) !!} {!! $currency !!})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="order-edit__delivery order-delivery">
                        <div class="order-delivery__title">Доставка:</div>

                        <div class="order-edit__select order-select">
                            <select name="delivery_date" id="order-select-date">
                                @foreach($delivery_dates as $date)
                                    <option value="{!! $date->format('d-m-Y') !!}"
                                            @if ($date == $order->getDeliveryDate()) selected @endif>
                                        {!! get_localized_date($date) !!}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="order-delivery__time">
                            @foreach($delivery_times as $key => $time)
                                <div class="order-delivery__time-radio order-delivery-radio">
                                    <input type="radio" id="order-del-radio-{!! $key !!}"
                                           name="delivery_time"
                                           @if ($time == $order->delivery_time) checked @endif
                                           value="{!! $time !!}">
                                    <label for="order-del-radio-{!! $key !!}">{!! $time !!}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (!$user->subscribe()->count())
                        <div class="order-edit__subscribe">
                            <div class="order-edit__subscribe-title">Подписка:</div>

                            @foreach($subscribe_periods as $period => $label)
                                <div class="order-edit__subscribe-checkbox square-red-checkbox">
                                    <input type="checkbox" id="order-subscribe-{!! $period !!}" name="subscribe_period"
                                           value="{!! $period !!}"
                                           @if ($period == $order->subscribe_period) checked @endif>
                                    <label for="order-subscribe-{!! $period !!}">{!! $label !!}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="order-edit__payment">
                        <div class="order-edit__payment-title">Оплата:</div>

                        @foreach($payment_methods as $id => $payment_method)
                            <div class="order-edit__payment-checkbox square-red-checkbox">
                                <input type="radio" id="order-payment-{!! $id !!}" name="payment_method"
                                       value="{!! $id !!}"
                                       @if ($id == $order->payment_method) checked @endif>
                                <label for="order-payment-{!! $id !!}">{!! $payment_method !!}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-edit__address">
                        <div class="order-edit__address-title">Адрес:</div>

                        <div class="order-edit__address-wrapper">
                            <div class="order-edit__select order-select order-edit__address-input">
                                <select name="city_id" id="order-edit-city-id">
                                    @foreach($cities as $city)
                                        <option value="{!! $city->id !!}"
                                                @if ($city->id == $order->city_id) selected @endif>
                                            {!! $city->name !!}
                                        </option>
                                    @endforeach
                                    <option value="0" @if (!$order->city_id) selected @endif>
                                        Другой
                                    </option>
                                </select>
                            </div>

                            <textarea id="order-edit-city-name" cols="30" rows="3"
                                      class="order-edit__address-input @if (empty($order->city_name)) h-hidden @endif"
                                      name="city_name"
                                      placeholder="Город">{!! $order->city_name !!}</textarea>

                            <textarea id="order-edit-address" cols="30" rows="3"
                                      class="order-edit__address-input"
                                      name="address"
                                      placeholder="Ваш адрес">{!! $order->address !!}</textarea>

                            <div class="order-edit__address-inner" data-info="change">
                                <span class="order-edit__address-text"
                                      data-active>{!! $order->getFullAddress() !!}</span>
                                <div class="order-edit__address-change" data-info="change">
                                    <span>(Изменить)</span>
                                    <span>Сохранить</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <textarea name="comment" id="order-edit-comment" cols="30" rows="7"
                              class="order-edit__comment textarea-order-edit"
                              placeholder="Комментарии к заказу">{!! $order->comment !!}</textarea>

                    <div class="order-edit__total">
                        <div class="order-edit__total-wrapper">
                            <div class="order-edit__total-title">Итого:</div>
                            <div id="order_total_desktop" class="order-edit__total-value"
                                 data-total="{!! $order->total !!}">
                                {!! $order->total !!}<span>{!! $currency !!}</span>
                            </div>
                        </div>
                    </div>

                    <div name="order-submit" class="order-edit__make-order">Заказать</div>
                </div>
            </div>
        </form>
    </div>

@endsection