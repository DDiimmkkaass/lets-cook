<section class="order__address-and-date order-addr-date" data-steps="4">
    <div class="order-addr-date__header">
        <h2 class="order-addr-date__title">
            <span class="georgia-title" data-device="mobile">Доставка</span>
            <span class="georgia-title" data-device="desktop">Детали доставки</span>
        </h2>

        @if (!$user)
            <div class="order-addr-date__haveDone">Уже заказывали у нас?</div>

            <div class="order-addr-date__signIn">
                <a href="#">Авторизируйтесь</a>, и мы заполним детали доставки автоматически
            </div>
        @endif
    </div>

    <div class="order-addr-date__wrapper">
        <div class="order-addr-date__inner" data-order="1">
            <div class="order-addr-date__subTitle">Дата и время</div>

            <div class="order-addr-date__date-select main-select" data-select="date-time">
                <label for="f-select-date">Дата</label>
                <select class="main-select__wrapper" name="delivery_date" id="f-select-date" required>
                    <option value="" disabled selected hidden>Выберите дату</option>
                    @foreach($delivery_dates as $date)
                        <option value="{!! $date->format('d-m-Y') !!}">{!! get_localized_date($date) !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="order-addr-date__date-select main-select" data-select="date-time">
                <label for="f-select-time">Время</label>
                @php($_time = empty($repeat_order) ? '' : $repeat_order->delivery_time)
                <select class="main-select__wrapper" name="delivery_time" id="f-select-time" required>
                    <option value="" disabled selected hidden>Выберите время</option>
                    @foreach($delivery_times as $time)
                        <option value="{!! $time !!}" @if ($time == $_time) selected="selected" @endif>
                            {!! $time !!}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="order-addr-date__wrapper">
        <div class="order-addr-date__inner" data-order="2">
            <div class="order-addr-date__subTitle">Адрес</div>

            <div class="order-addr-date__date-select main-select" data-select="city">
                <label for="f-select-city">Город</label>
                @php($_city_id = empty($repeat_order) ? ($user ? $user->city_id : '' ) : $repeat_order->city_id)
                @php($_city_name = empty($repeat_order) ? ($user ? $user->city_name : '') : $repeat_order->city_name)
                <select class="main-select__wrapper" name="city_id" id="f-select-city" required>
                    <option value="" disabled selected hidden>Выберите город</option>
                    @foreach($cities as $city)
                        <option value="{!! $city->id !!}" @if ($city->id == $_city_id) selected="selected" @endif>
                            {!! $city->name !!}
                        </option>
                    @endforeach
                    <option value="0" @unless (empty($_city_name)) selected="selected" @endunless>Другой..</option>
                </select>
            </div>

            <div class="order-addr-date__date-select" data-select="city-name"
                 @unless (empty($_city_name)) data-active @endunless>
                <label for="f-select-address">Др. город</label>
                <input type="text" id="f-select-other-city" class="order-addr-date__input input-text-small"
                       name="city_name" placeholder="Укажите город"
                       value="{!! $_city_name !!}">
            </div>

            <div class="order-addr-date__date-select" data-select="address">
                <label for="f-select-address">Адрес</label>
                <input type="text" id="f-select-address" class="order-addr-date__input input-text-small"
                       name="address" placeholder="Укажите адрес"
                       value="{!! empty($repeat_order) ? ($user ? $user->address : '') : $repeat_order->address !!}">
            </div>
        </div>
    </div>

    <div class="order-addr-date__comment">
        <div class="order-addr-date__comment-wrapper">
            <div class="order-addr-date__comment-title">
                <span data-device="desktop">
                    @lang('front_texts.comment helper text on order page')
                </span>
                <span data-device="mobile">Комментарий:</span>
            </div>

            @php($comment = empty($repeat_order) ? ($user ? $user->comment : '') : $repeat_order->comment)
            <input type="text" class="order-addr-date__input input-text-small" name="comment"
                   placeholder="Комментарий" value="{!! $comment !!}">

        </div>

        <div class="order-addr-date__terms square-checkbox">
            <div class="square-checkbox__wrapper">
                <input type="checkbox" name="terms" id="f-addr-date__terms" value="true">
                <label for="f-addr-date__terms"></label>
            </div>

            <label class="square-checkbox__text" for="f-addr-date__terms">
                <a href="{!! localize_url('/pages/dostavka-i-oplata') !!}" target="_blank">Условия доставки</a> знаю и
                принимаю, гарантирую присутствие дома кого-либо для получения заказа
            </label>
        </div>
    </div>
</section>