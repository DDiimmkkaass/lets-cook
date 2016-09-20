<section class="order__main order-main">
    <input type="hidden" name="basket_id" value="{!! $basket->id !!}">

    <div class="order-main__wrapper">
        <div class="order-main__top">
            <div class="order-main__left">
                <h1 class="order-main__title">{!! $basket->getName() !!}</h1>
                <div class="order-main__subTitle">На приготовление одного блюда понадобится не более 40 минут
                </div>

                <div class="order-main__result">
                    <div class="order-main__result-wrapper">
                        <ul class="order-main__count-list">
                            <li class="order-main__count-item">
                                <span>{!! $basket->recipes->count() !!}</span>
                                <span>@choice('front_labels.count_of_dinners', $basket->recipes->count())</span>
                            </li>
                            <li class="order-main__count-item">
                                <span>{!! $basket->portions !!}</span>
                                <span>@choice('front_labels.count_of_portions', $basket->portions)</span>
                            </li>
                        </ul>

                        <div class="order-main__price" data-device="mobile">
                            {!! $basket->getPriceInOrder() !!}<span>{!! $currency !!}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-main__right">
                <div class="order-main__price">
                    {!! $basket->getPriceInOrder() !!}
                    <span>{!! $currency !!}</span>
                </div>
                <a href="#" class="order-main__make-order black-short-button" data-device="desktop">
                    Оформить заказ
                </a>
            </div>
        </div>

        <a href='#' class="order-main__make-order ptsans-narrow-regular-tittle" data-device="mobile">
            Оформить заказ
        </a>

        @if ($basket->recipes->count())
            <ul class="order-main__list">
                @foreach($basket->recipes as $key => $recipe)
                    <li class="order-main__item">
                        <a href="{!! $recipe->recipe->getUrl() !!}" class="order-main__link">
                            <div class="order-main__img"
                                 style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 130) !!});">
                            </div>
                            <h3 class="order-main__item-title">
                                @choice('front_labels.day_with_number', $key + 1): {!! $recipe->getRecipeName() !!}
                            </h3>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="order-main__desc">
            {!! $basket->getDescription() !!}
        </div>
    </div>
</section>