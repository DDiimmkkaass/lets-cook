<div class="header__restore restore">
    <div class="restore__wrapper">
        <div class="restore__title">Запрос на востановление пароля</div>

        <div class="restore__content">
            <form action="{!! localize_route('auth.restore.post') !!}" class="restore__form">
                {!! csrf_field() !!}
                <input type="text" name="email" placeholder="Адрес электронной почты"
                       class="input-text-small" required>

                <input type="submit" name="restore__submit" class="black-long-button" value="Востановить">
            </form>
        </div>

        <div class="restore__close"></div>
    </div>

    <div class="restore__close-layout"></div>
</div>