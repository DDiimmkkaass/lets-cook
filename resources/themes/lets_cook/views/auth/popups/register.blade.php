<div class="header__sign-out sign-out">
    <div class="sign-out__wrapper">
        <div class="sign-out__title georgia-title">регистрация</div>

        <div class="sign-out__content">
            <form action="{!! localize_route('auth.register.post') !!}" method="post" class="sign-out__form">
                {!! csrf_field() !!}

                <div class="sign-out__side" data-reg="left">
                    <div class="sign-out__form-title">Обязательно заполните</div>
                    <input type="text" name="sign-out__name" placeholder="Как вас зовут" class="input-text-small"
                           required>
                    <input type="text" name="sign-out__email" placeholder="Адрес электронной почты"
                           class="input-text-small" required>
                    <input type="password" name="sign-out__pass" placeholder="Придумайте пароль"
                           class="input-text-small" required>
                    <input type="text" name="sign-out__phone" placeholder="Ваш телефон" class="input-text-small"
                           required>
                    <input type="text" name="sign-out__other-phone"
                           placeholder="Другой номер, если основной недоступен" class="input-text-small">
                </div>

                <div class="sign-out__side" data-reg="right">
                    <div class="sign-out__form-desc">Регистрация нужна для организации личного кабинета, где будет
                        храниться
                        история заказов, контактная информация и прочие данные
                    </div>

                    <table class="sign-out__radio-wrapper">
                        <tr class="sign-out__radio">
                            <td><label for="f-sign-out__radio-1">Вас поздравлять с 8 марта</label></td>
                            <td><input type="radio" id="f-sign-out__radio-1" name="sign-out__radio" value="female"
                                       required><label for="f-sign-out__radio-1"></label></td>
                        </tr>

                        <tr class="sign-out__radio">
                            <td><label for="f-sign-out__radio-2">Вас поздравлять с 23 февраля</label></td>
                            <td><input type="radio" id="f-sign-out__radio-2" name="sign-out__radio" value="male"
                                       required><label for="f-sign-out__radio-2"></label></td>
                        </tr>
                    </table>

                    <div class="sign-out__birthday">
                        <label for="f-sign-out__birthday">В день рождения мы дадим вам дополнительные скидки,
                            скажите, когда?</label>
                        <input type="text" id="f-sign-out__birthday" name="sign-out__birthday"
                               class="input-text-small" placeholder="День, месяц, год вашего рождения" required>
                    </div>

                    <div class="sign-out__about">
                        <label for="f-sign-out__select">И просто для статистики, откуда вы про нас узнали?</label>

                        <div class="sign-out__select-wrapper main-select">
                            <select class="main-select__wrapper" name="sign-out__select" id="f-sign-out__select"
                                    required>
                                <option value="" disabled selected hidden>Откуда вы о нас узнали? Выберите
                                    источник
                                </option>
                                @foreach(config('user.sources') as $source)
                                    <option value="{!! $source !!}">{!! $source !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="submit" name="sign-out__submit" class="black-long-button" value="зарегистрировать">
                </div>
            </form>
        </div>

        <div class="sign-out__close"></div>
    </div>

    <div class="sign-out__close-layout"></div>
</div>