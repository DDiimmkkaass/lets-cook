<section class="order__ingredients order-ing">
    <a href='#' class="order-ing__title ptsans-narrow-regular-tittle">
        <span>Ингредиенты</span>
        <span>Свернуть</span>
    </a>

    <div class="order-ing__lists">
        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Ингредиенты</h3>

            <ul class="order-ing__list">
                @foreach($basket->main_recipes as $recipe)
                    @foreach($recipe->recipe->ingredients as $ingredient)
                        <li>{!! $ingredient->ingredient->getTitle() !!}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>

        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Понадобится на кухне</h3>

            <ul class="order-ing__list">
                @foreach($basket->main_recipes as $recipe)
                    <li>{!! $recipe->recipe->home_equipment !!}</li>
                @endforeach
            </ul>
        </div>

        <div class="order-ing__list-wrapper">
            <h3 class="order-ing__list-title">Должно быть дома</h3>

            <ul class="order-ing__list">
                @foreach($basket->main_recipes as $recipe)
                    @foreach($recipe->recipe->home_ingredients as $ingredient)
                        <li>
                            <span>{!! $ingredient->ingredient->getTitle() !!}</span>

                            <div class="checkbox-button">
                                <input type="checkbox"
                                       id="f-order-ing-{!! $recipe->id !!}-{!! $ingredient->id !!}"
                                       name="order-ingridients"
                                       class="checkbox-button"
                                       href="#"
                                       data-id="{!! $recipe->id !!}-{!! $ingredient->id !!}"><label
                                        for="f-order-ing-{!! $recipe->id !!}-{!! $ingredient->id !!}"
                                        data-add="Добавить"
                                        data-remove="Убрать">Добавить</label>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</section>