<div class="header__sign-in sign-in">
    <div class="sign-in__wrapper">
        <div class="sign-in__title">Вход в личный кабинет</div>

        <div class="sign-in__helper">
            <div class="sign-in__helper_close"></div>
            @lang('front_texts.login info text for old users')
        </div>

        <div class="sign-in__content">
            <form action="{!! localize_route('auth.login.post') !!}" class="sign-in__form">
                {!! csrf_field() !!}
                <input type="text" name="sign-in__mail" placeholder="Адрес электронной почты"
                       class="input-text-small" required>
                <input type="password" name="sign-in__pass" placeholder="Пароль" class="input-text-small" required>
                <input type="submit" name="sign-in__submit" class="black-long-button" value="Войти">
            </form>

            <a class="sign-in__restore-button">Забыли пароль?</a>
        </div>

        <div class="sign-in__close"></div>
    </div>

    <div class="sign-in__close-layout"></div>
</div>