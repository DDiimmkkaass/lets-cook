<section class="order__address-and-date order-addr-date">
    <h2 class="order-addr-date__title">
        <span class="georgia-title" data-device="mobile">Доставка</span>
        <span class="georgia-title" data-device="desktop">адрес и время доставки</span>
    </h2>

    <div class="order-addr-date__wrapper">
        <div class="order-addr-date__inner" data-order="1">
            <div class="order-addr-date__subTitle">Дата и время</div>

            <div class="order-addr-date__date-select main-select" data-select="date">
                <label for="f-select-date">Дата</label>
                <select class="main-select__wrapper" name="sign-out__select" id="f-select-date" required>
                    <option value="" disabled selected hidden>Выберите дату</option>
                    @foreach($delivery_dates as $date)
                        <option value="{!! $date->format('d-m-Y') !!}">{!! get_localized_date($date) !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="order-addr-date__date-select main-select" data-select="time">
                <label for="f-select-time">Время</label>
                <select class="main-select__wrapper" name="sign-out__select" id="f-select-time" required>
                    <option value="" disabled selected hidden>Выберите время</option>
                    @foreach($delivery_times as $time)
                        <option value="{!! $time !!}">{!! $time !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="order-addr-date__wrapper">
        <div class="order-addr-date__inner" data-order="2">
            <div class="order-addr-date__subTitle">Адрес</div>

            <div class="order-addr-date__date-select main-select" data-select="city">
                <label for="f-select-city">Адрес</label>
                <select class="main-select__wrapper" name="addr-date__select" id="f-select-city" required>
                    <option value="" disabled selected hidden>Выберите город</option>
                    @foreach($cities as $city)
                        <option value="{!! $city->id !!}">{!! $city->name !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="order-addr-date__date-select" data-select="address">
                <label for="f-select-address">Адрес</label>
                <input type="text" id="f-select-address" class="order-addr-date__input input-text-small"
                       name="addr-date__address" placeholder="Укажите адрес">
            </div>
        </div>
    </div>

    <div class="order-addr-date__comment">
        <div class="order-addr-date__comment-wrapper">
            <div class="order-addr-date__comment-title">
                <span data-device="desktop">Дополнительно сообщите о наличии консьержа или домофона, а также номер поъезда и особенности ориентирования на местности</span>
                <span data-device="mobile">Комментарий:</span>
            </div>

            <input type="text" class="order-addr-date__input input-text-small" name="addr-date__comment"
                   placeholder="Комментарий">

            <div class="order-addr-date__terms square-checkbox">

                <div class="square-checkbox__wrapper">
                    <input type="checkbox" name="addr-date__terms" id="f-addr-date__terms" value="true">
                    <label for="f-addr-date__terms"></label>
                </div>

                <label class="square-checkbox__text" for="f-addr-date__terms"><a href="#" target="_blank">Условия
                        доставки</a> знаю и принимаю, гарантирую присутствие дома кого-либо для получения заказа</label>
            </div>
        </div>
    </div>
</section>