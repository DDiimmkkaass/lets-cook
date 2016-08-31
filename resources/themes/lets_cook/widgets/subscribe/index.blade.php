<section class="main-subscribe">
    <h2 class="main-subscribe__title">будьте в курсе</h2>

    <div class="main-subscribe__desc">
        Получать раз в неделю информацию о специальных предложениях, акциях и изменениях меню
    </div>

    <form method="post" action="{!! route('subscribes.store') !!}" class="main-subscribe__form subscribe-form">
        {!! csrf_field() !!}

        <input type="text" name="subscribe-mail" placeholder="Введите адрес электронной почты"
               class="input-text-large">

        <input type="submit" name="subscribe-submit" value="Подписаться">
    </form>
</section>