<section class="order__main order-main" data-steps="1">
    <input type="hidden" name="basket_id" value="{!! $basket->id !!}">
    <input type="hidden" name="basket_slug" value="{!! $basket->getSlug() !!}">

    <div class="order-main__wrapper">
        <div class="order-main__top">
            <div class="order-main__left">
                <h1 class="order-main__title">{!! $basket->getName() !!}</h1>
                <div class="order-main__subTitle">
                    @lang('front_texts.basket description on order page')
                </div>

                @php($_recipes_count = $basket->getRecipesCount())
                <div class="order-main__result">
                    <div class="order-main__result-wrapper">
                        <div class="order-main__count">
                            <div class="order-main__count-title">Ужинов</div>
                            <ul class="order-main__count-list recipes">
                                @for ($i = 1; $i <= 7; $i++)
                                    <li class="order-main__count-item order-count-radio @if ($i <= $_recipes_count) exists @endif">
                                        <input type="radio" id="order-count-radio-{!! $i !!}"
                                               name="recipes_count"
                                               @if (
                                                ($_recipes_count < $recipes_count && $i == $_recipes_count)
                                                ||
                                                (($_recipes_count >= $recipes_count) && $i == $recipes_count)
                                                )
                                               checked
                                               @endif
                                               value="{!! $i !!}"
                                               data-count="{!! $i !!}">
                                        <label class="@if ($i > $_recipes_count) not-exists @endif" for="order-count-radio-{!! $i !!}">{!! $i !!}</label>
                                    </li>
                                @endfor
                            </ul>
                        </div>

                        <div class="order-main__count">
                            <div class="order-main__count-title">Порций</div>
                            <ul class="order-main__count-list order-portions-count">
                                @php($_basket = $basket->portions == 2 ? $basket : ($same_basket ? $same_basket : null))
                                @if ($_basket)
                                    <li class="order-main__count-item order-portions-count-radio">
                                        <input type="radio" id="order-portions-count-radio-2"
                                               name="portions"
                                               @if ($_basket->portions == $basket->portions)
                                               checked
                                               @endif
                                               data-basket_id="{!! $basket->id !!}"
                                               data-href="{!! $_basket->getUrl() !!}"
                                               value="2"
                                               data-count="2">
                                        <label for="order-portions-count-radio-2">2</label>
                                    </li>
                                @endif

                                @php($__basket = $basket->portions == 4 ? $basket : ($same_basket ? $same_basket : null))
                                @if ($__basket)
                                    <li class="order-main__count-item order-portions-count-radio">
                                        <input type="radio" id="order-portions-count-radio-4"
                                               name="portions"
                                               @if ($__basket->portions == $basket->portions)
                                               checked
                                               @endif
                                               data-basket_id="{!! $__basket->id !!}"
                                               data-href="{!! $__basket->getUrl() !!}"
                                               value="4"
                                               data-count="4">
                                        <label for="order-portions-count-radio-4">4</label>
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
                    @foreach($basket->prices as $dinners => $price)
                        <input type="hidden" class="basket-{!! $basket->portions !!}-price-{!! $dinners !!}"
                               value="{!! $price !!}">
                    @endforeach
                    @if ($same_basket)
                        @foreach($same_basket->prices as $dinners => $price)
                            <input type="hidden" class="basket-{!! $same_basket->portions !!}-price-{!! $dinners !!}"
                                   value="{!! $price !!}">
                        @endforeach
                    @endif
                </div>

                <div class="order-main__portion">
                    <div class="order-main__portion-title">За порцию</div>
                    <div id="per_portion_total" class="order-main__portion-value">
                        {!! round($basket->getPriceInOrder($recipes_count) / $basket->portions / $recipes_count) !!}
                        <span>{!! $currency !!}</span>
                    </div>
                </div>

                <div href="#" class="order-main__make-order" data-device="desktop"></div>
            </div>
        </div>

        <ul class="order-main__list">
            @foreach($basket->recipes->keyBy('position') as $key => $recipe)
                <li class="order-main__item">
                    <a target="_blank" href="{!! $recipe->recipe->getUrl() !!}" class="order-main__link">
                        <div class="order-main__img"
                             style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 130) !!});">
                        </div>
                        <h3 class="order-main__item-title">
                            @choice('front_labels.day_with_number', $key): {!! $recipe->getRecipeName() !!}
                        </h3>
                    </a>
                    <input class="h-hidden" type="checkbox" data-recipe_id="{!! $recipe->id !!}" name="recipes[{!! $key !!}]" value="{!! $key !!}">
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