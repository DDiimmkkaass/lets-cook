<section class="main-subscribe">
    <h2 class="main-subscribe__title">@lang('front_texts.subscribe widget title')</h2>

    <div class="main-subscribe__desc">
        @lang('front_texts.subscribe widget description')
    </div>

    <form method="post" action="{!! route('subscribes.store') !!}" class="main-subscribe__form subscribe-form">
        {!! csrf_field() !!}

        <input type="text" name="subscribe-mail" placeholder="Введите адрес электронной почты"
               class="input-text-large">

        <input type="submit" name="subscribe-submit" value="Подписаться">
    </form>
</section>