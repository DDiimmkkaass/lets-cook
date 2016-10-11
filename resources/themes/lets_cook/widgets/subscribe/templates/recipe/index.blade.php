<section class="recipe-simple__subscribe recipe-simple-subscribe">
    <h2 class="recipe-simple-subscribe__title georgia-title">@lang('front_texts.subscribe widget title for recipe page')</h2>

    <div class="recipe-simple-subscribe__desc">
        @lang('front_texts.subscribe widget description for recipe page')
    </div>

    <form action="{!! route('subscribes.store') !!}" class="recipe-simple-subscribe__form subscribe-form">
        {!! csrf_field() !!}

        <input type="text"
               name="subscribe-mail"
               placeholder="Введите адрес электронной почты"
               class="input-text-large">

        <input type="submit" name="subscribe-submit" value="Подписаться">
    </form>
</section>