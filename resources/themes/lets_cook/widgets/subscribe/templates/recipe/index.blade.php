<section class="recipe-simple__subscribe recipe-simple-subscribe">
    <h2 class="recipe-simple-subscribe__title georgia-title">Понравился рецепт?</h2>

    <div class="recipe-simple-subscribe__desc">Подпишитесь на нашу рассылку и мы будем присылать вам по 3
        интересных блюда каждую неделю
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