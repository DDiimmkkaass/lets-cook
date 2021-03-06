@if ($menu || $next_menu)
    <section class="recipes-and-baskets">

        <div class="recipes-and-baskets__item recipes-menu">
            <ul class="recipes-menu__choose">
                <li class="recipes-menu__chooseItem" data-show="false" data-week="0">
                    Меню на эту неделю
                </li>
                <li class="recipes-menu__chooseItem" data-show="false" data-week="1">
                    Меню на следующую неделю
                </li>
            </ul>

            <ul class="recipes-menu__hidden">
                @if ($menu)
                    @foreach($menu_baskets as $key => $basket)
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="0" data-basket="{!! $basket->basket_id !!}">
                                <a href="{!! $basket->getUrl() !!}"
                                   title="{!! $recipe->getRecipeName() !!}"
                                   class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url({!! thumb($recipe->recipe->image, 410, 191) !!});"></div>
                                    <h3 class="recipes-menu__title">
                                        {!! $recipe->getRecipeName() !!}
                                    </h3>
                                </a>
                            </li>
                            @break($_key >= 4)
                        @endforeach
                    @endforeach
                    @if ($new_year_basket)
                        @foreach($new_year_basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="0" data-basket="1000">
                                <a href="{!! $new_year_basket->getUrl() !!}"
                                   title="{!! $recipe->getRecipeName() !!}"
                                   class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url({!! thumb($recipe->recipe->image, 410, 191) !!});"></div>
                                    <h3 class="recipes-menu__title">
                                        {!! $recipe->getRecipeName() !!}
                                    </h3>
                                </a>
                            </li>
                            @break($_key >= 4)
                        @endforeach
                    @endif
                @endif

                @if ($next_menu)
                    @foreach($next_menu_baskets as $key => $basket)
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="1" data-basket="{!! $basket->basket_id !!}">
                                <a href="{!! $basket->getUrl('next') !!}"
                                   title="{!! $recipe->getRecipeName() !!}"
                                   class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url({!! thumb($recipe->recipe->image, 410, 191) !!});"></div>
                                    <h3 class="recipes-menu__title">
                                        {!! $recipe->getRecipeName() !!}
                                    </h3>
                                </a>
                            </li>
                            @break($_key >= 4)
                        @endforeach
                    @endforeach
                @endif
            </ul>

            <div class="recipes-menu__content">
                <ul class="recipes-menu__list"></ul>
                <a href="#" class="recipes-menu__all yellow-small-button">Все рецепты</a>
            </div>
        </div>

        <div class="recipes-and-baskets__item baskets-menu">
            <h2 class="baskets-menu__title">наши корзины</h2>

            <ul class="baskets-menu__info">
                <li class="baskets-menu__info-item"><span data-info="number">2/4</span><br>порции</li>
                <li class="baskets-menu__info-item"><span data-info="number">1-7</span><br>ужинов</li>
                <li class="baskets-menu__info-item">Любой рецепт<br><span data-info="text">можно заменить</span></li>
            </ul>


            <ul class="baskets-menu__main-list">
                @foreach($baskets as $basket)
                    @if ($basket->getSlug() == variable('new_year_basket_slug') && $new_year_basket)
                        <li class="baskets-menu__main-item"
                            data-basket="1000"
                            data-active="false"
                            data-current-week="true"
                            data-current-week-url="{!! $new_year_basket->getUrl() !!}"
                            data-delivery_dates="{!! implode(' или ', $new_year_delivery_dates) !!}">
                            {!! $new_year_basket->getName() !!}
                        </li>
                    @else
                        @php($_menu = $menu_baskets->get($basket->id))
                        @php($_next_menu = $next_menu_baskets->get($basket->id))

                        @if ($_menu || $_next_menu)
                            <li class="baskets-menu__main-item"
                                data-basket="{!! $basket->id !!}"
                                data-active="false"
                                @if ($_menu)
                                data-current-week="true"
                                data-current-week-url="{!! $_menu->getUrl() !!}"
                                @endif
                                @if ($_next_menu)
                                data-next-week="true"
                                data-next-week-url="{!! $_next_menu->getUrl('next') !!}"
                                @endif

                                @if ($basket->getSlug() == variable('new_year_basket_slug') && !empty($new_year_delivery_dates))
                                data-delivery_dates="{!! implode(' или ', $new_year_delivery_dates) !!}"
                                @endif

                                >{!! $basket->getName() !!}</li>
                        @endif
                    @endif
                @endforeach
            </ul>

            @if ($menu)
                <div class="baskets-menu__desc" data-week="0">
                    Оформите сегодня, и вы
                    <span>получите заказ
<span class="delivery-dates" data-week="0" data-delivery_dates="{!! implode(' или ', $menu->getDeliveryDates()) !!}">
{!! implode(' или ', $menu->getDeliveryDates()) !!}</span></span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="#"
                   data-week="0"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif

            @if ($next_menu)
                <div class="baskets-menu__desc" data-week="1">
                    Оформите сегодня, и вы
                    <span>получите заказ
<span class="delivery-dates" data-week="1"
      data-delivery_dates="{!! implode(' или ', $next_menu->getDeliveryDates()) !!}">
{!! implode(' или ', $next_menu->getDeliveryDates()) !!}</span></span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="#"
                   data-week="1"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif
        </div>

    </section>
@endif