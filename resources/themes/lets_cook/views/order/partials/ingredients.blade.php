<section class="order__ingredients order-ing">
    <a href='#' class="order-ing__title ptsans-narrow-regular-tittle">
        <span>Ингредиенты</span>
        <span>Свернуть</span>
    </a>

    <div class="order-ing__lists">
        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Ингредиенты</h3>

            <ul class="order-ing__list">
                @foreach($basket->recipes as $recipe)
                    @foreach($recipe->recipe->ingredients as $ingredient)
                        <li class="recipe-{!! $recipe->id !!}-ingredient">
                            {!! $ingredient->ingredient->getTitle() !!}
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>

        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Понадобится на кухне</h3>

            <ul class="order-ing__list">
                @foreach($basket->recipes as $recipe)
                    <li class="recipe-{!! $recipe->id !!}-ingredient">{!! $recipe->recipe->home_equipment !!}</li>
                @endforeach
            </ul>
        </div>

        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Должно быть дома</h3>

            <ul class="order-ing__list">
                @php($i = 0)
                @php($viewed = [])
                @foreach($basket->recipes as $recipe)
                    @foreach($recipe->recipe->home_ingredients as $ingredient)
                        @if ($ingredient->ingredient->inSale() && !in_array($ingredient->ingredient_id, $viewed))
                            @php($viewed[] = $ingredient->ingredient_id)
                            <li class="recipe-{!! $recipe->id !!}-ingredient">
                                <span>{!! $ingredient->ingredient->getTitle() !!}</span>

                                <div class="checkbox-button">
                                    <input type="checkbox"
                                           id="f-order-ing-{!! $recipe->id !!}_{!! $ingredient->id !!}"
                                           data-name="ingredients[{!! $i !!}]"
                                           data-price="{!! $ingredient->getPriceInOrder() !!}"
                                           value="{!! $recipe->id !!}_{!! $ingredient->ingredient_id !!}"
                                           class="checkbox-button"
                                           data-id="{!! $recipe->id !!}_{!! $ingredient->id !!}">
                                    <label for="f-order-ing-{!! $recipe->id !!}_{!! $ingredient->id !!}"
                                           data-add="Добавить"
                                           data-remove="Убрать">
                                        Добавить
                                    </label>
                                </div>
                            </li>
                            @php($i++)
                        @endif
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>

    <div class="order-ing__show black-short-button">
        <span>Развернуть</span>
        <span>Свернуть</span>
    </div>
</section>