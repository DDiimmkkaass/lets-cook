<section class="order__main order-main">
    <input type="hidden" name="basket_id" value="{!! $basket->id !!}">

    <div class="order-main__wrapper">
        <div class="order-main__top">
            <div class="order-main__left">
                <h1 class="order-main__title">{!! $basket->getName() !!}</h1>
                <div class="order-main__subTitle">
                    На приготовление одного блюда понадобится не более 40 минут
                </div>

                @php($_recipes_count = $basket->getRecipesCount())
                <div class="order-main__result">
                    <div class="order-main__result-wrapper">
                        <div class="order-main__count">
                            <div class="order-main__count-title">Ужинов</div>
                            <ul class="order-main__count-list">
                                @if (!empty($trial))
                                    <li class="order-main__count-item order-count-radio">
                                        <input type="radio" id="order-count-radio-1" name="recipes_count"
                                               checked
                                               value="1"
                                               data-price="{!! $basket->getPriceInOrder(1) !!}"
                                               data-count="1">
                                        <label for="order-count-radio-1">1</label>
                                    </li>
                                @endif
                                @for ($i = 3; $i <= 7; $i++)
                                    <li class="order-main__count-item order-count-radio">
                                        <input type="radio" id="order-count-radio-{!! $i !!}"
                                               name="recipes_count"
                                               @if (!empty($trial))
                                               @else
                                                   @if (
                                                    ($_recipes_count >= 7 && $i == $recipes_count)
                                                    ||
                                                    ($_recipes_count < $recipes_count && $i == $_recipes_count)
                                                    )
                                                   checked
                                               @endif
                                               @endif
                                               value="{!! $i !!}"
                                               data-price="{!! $basket->getPriceInOrder($i) !!}"
                                               data-count="{!! $i !!}">
                                        <label for="order-count-radio-{!! $i !!}">{!! $i !!}</label>
                                    </li>
                                @endfor
                            </ul>
                        </div>

                        <div class="order-main__count">
                            <div class="order-main__count-title">Порций</div>
                            <ul class="order-main__count-list">
                                @if ($basket->portions == 2)
                                    <li class="order-main__count-item" data-active>
                                        <a href="{!! $basket->getUrl() !!}">2</a>
                                    </li>
                                @endif
                                @if ($same_basket && $same_basket->portions == 2)
                                    <li class="order-main__count-item">
                                        <a href="{!! $same_basket->getUrl() !!}">2</a>
                                    </li>
                                @endif
                                @if ($basket->portions == 4)
                                    <li class="order-main__count-item" data-active>
                                        <a href="{!! $basket->getUrl() !!}">4</a>
                                    </li>
                                @endif
                                @if ($same_basket && $same_basket->portions == 4)
                                    <li class="order-main__count-item">
                                        <a href="{!! $same_basket->getUrl() !!}">4</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-main__right">
                <div class="order-main__price">
                    <div class="order-main__price-title">Стоимость корзины</div>
                    <div id="order_total_mobile" class="order-main__price-value">
                        {!! $basket->getPriceInOrder($recipes_count) !!}<span>{!! $currency !!}</span>
                    </div>
                </div>

                <div class="order-main__portion">
                    <div class="order-main__portion-title">За порцию</div>
                    <div id="per_portion_total" class="order-main__portion-value">
                        {!! round($basket->getPriceInOrder($recipes_count) / $basket->portions / $recipes_count) !!}
                        <span>{!! $currency !!}</span>
                    </div>
                </div>

                <a href="#" class="order-main__make-order black-short-button" data-device="desktop">
                    Заказать в один клик
                </a>
            </div>
        </div>

        <a href='#' class="order-main__make-order ptsans-narrow-regular-tittle" data-device="mobile">
            Заказать в один клик
        </a>


        <ul class="order-main__list">
            @foreach($basket->recipes as $key => $recipe)
                <li class="order-main__item">
                    <a target="_blank" href="{!! $recipe->recipe->getUrl() !!}" class="order-main__link">
                        <div class="order-main__img"
                             style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 130) !!});">
                        </div>
                        <h3 class="order-main__item-title">
                            @choice('front_labels.day_with_number', $key + 1): {!! $recipe->getRecipeName() !!}
                        </h3>
                    </a>
                    <input class="h-hidden" type="checkbox" name="recipes[{!! $key !!}]" value="{!! $recipe->id !!}">
                </li>
            @endforeach
        </ul>


        <div class="order-main__bottom">
            <div class="order-main__desc">
                {!! $basket->getDescription() !!}
            </div>

            <div class="order-main__edit black-short-button">Редактировать</div>
        </div>
    </div>
</section>