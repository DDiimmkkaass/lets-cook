<li class="baskets-main__item baskets-main-item">
    <div class="baskets-main-item__top">
        <div class="baskets-main-item__left">
            <h2 class="baskets-main-item__title">
                <a title="{!! $basket->getName() !!}"
                   href="{!! $basket->getUrl($week) !!}"
                   data-device="mobile">
                    {!! $basket->getName() !!}
                </a>
                <a title="{!! $basket->getName() !!}"
                   href="{!! $basket->getUrl($week) !!}"
                   data-device="desktop">
                    {!! $basket->getName() !!}
                </a>
            </h2>

            <div class="baskets-main-item__result">
                <div class="baskets-main-item__result-wrapper">
                    <div class="baskets-main-item__price" data-device="mobile">
                        {!! $basket->getPriceInOrder(config('weekly_menu.default_recipes_count')) !!}
                        <span>{!! $currency !!}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="baskets-main-item__right">
            <div class="baskets-main-item__price" data-device="desktop">
                {!! $basket->getPriceInOrder(config('weekly_menu.default_recipes_count')) !!}
                <span>{!! $currency !!}</span>
            </div>
            @if (empty($next))
                <a href="{!! $basket->getUrl($week) !!}"
                   class="baskets-main-item__make-order"
                   data-device="desktop">
                    Оформить заказ
                </a>
            @endif
        </div>
    </div>

    <div class="baskets-main-item__main">
        <div class="baskets-main-item__first">
            <a href="{!! $basket->getUrl($week) !!}"
               class="baskets-main-item__link">
                <div class="baskets-main-item__img"
                     style="background-image: url({!! thumb($basket->getImage(), 980, 335) !!}); height: 335px"></div>
                <h3 class="baskets-main-item__main-title">
                    {!! $basket->getDescription() !!}
                </h3>
            </a>
        </div>

        @if ($basket->getRecipesCount())
            <ul class="baskets-main-item__list">
                @foreach($basket->recipes->keyBy('position')->take(5) as $key => $recipe)
                    <li class="baskets-main-item__item">
                        <a href="{!! $basket->getUrl($week) !!}"
                           class="baskets-main-item__link">
                            <div class="baskets-main-item__img"
                                 style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 134) !!});"></div>
                            <h3 class="baskets-main-item__item-title">
                                @choice('front_labels.day_with_number', $key)
                                : {!! $recipe->getRecipeName() !!}
                            </h3>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</li>