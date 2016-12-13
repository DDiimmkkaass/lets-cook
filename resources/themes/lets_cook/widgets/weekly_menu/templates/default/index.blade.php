@if ($menu || $next_menu)
    <section class="recipes-and-baskets">

        <div class="recipes-and-baskets__item recipes-menu">
            <ul class="recipes-menu__choose">
                @if ($menu)
                    <li class="recipes-menu__chooseItem"
                        data-week="0">
                        Меню на эту неделю
                    </li>
                @endif
                @if ($next_menu)
                    <li class="recipes-menu__chooseItem"
                        data-week="1">
                        Меню на следующую неделю
                    </li>
                @endif
            </ul>

            <ul class="recipes-menu__hidden">
                @if ($menu)
                    @foreach($menu_baskets as $key => $basket)
                        @if ($key == 0)
                            @php($active_basket = $basket)
                            @php($week = 'current')
                        @endif
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="0" data-basket="{!! $key !!}">
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
                        @if ($key == 0 && !isset($active_basket))
                            @php($active_basket = $basket)
                            @php($week = 'next')
                        @endif
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="1" data-basket="{!! $key !!}">
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
                <ul class="recipes-menu__list">

                </ul>

                <a href="{!! isset($active_basket) ? $active_basket->getUrl($week) : '#' !!}"
                   class="recipes-menu__all yellow-small-button">Все рецепты</a>
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
                @if ($menu)
                    <li class="baskets-menu__main-item" data-week="0">
                        <ul class="baskets-menu__sub-list">
                            @foreach($menu_baskets as $key => $basket)
                                <li class="baskets-menu__sub-item"
                                    data-url="{!! $basket->getUrl() !!}"
                                    @if ($key == 0) data-active-item @endif
                                    data-week="0"
                                    data-basket="{!! $key !!}">
                                    {!! $basket->getName() !!}
                                </li>
                            @endforeach
                            @if ($new_year_basket)
                                <li class="baskets-menu__sub-item"
                                    data-url="{!! $new_year_basket->getUrl() !!}"
                                    data-week="0"
                                    data-delivery_dates="{!! implode(' или ', $new_year_delivery_dates) !!}"
                                    data-basket="1000">
                                    {!! $new_year_basket->getName() !!}
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($next_menu)
                    <li class="baskets-menu__main-item" data-week="1">
                        <ul class="baskets-menu__sub-list">
                            @foreach($next_menu_baskets as $key => $basket)
                                <li class="baskets-menu__sub-item"
                                    data-url="{!! $basket->getUrl('next') !!}"
                                    @if ($key == 0) data-active-item @endif
                                    data-week="1"
                                    data-basket="{!! $key !!}">
                                    {!! $basket->getName() !!}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>

            @if ($menu)
                <div class="baskets-menu__desc" data-week="0">
                    Оформите сегодня, и вы
                    <span>получите заказ
                        <span class="delivery-dates" data-delivery_dates="{!! implode(' или ', $menu->getDeliveryDates()) !!}">
                            {!! implode(' или ', $menu->getDeliveryDates()) !!}</span></span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="{!! $active_basket->getUrl() !!}"
                   data-week="0"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif

            @if ($next_menu)
                <div class="baskets-menu__desc" data-week="1">
                    Оформите сегодня, и вы
                    <span>получите заказ
                        <span class="delivery-dates" data-delivery_dates="{!! implode(' или ', $next_menu->getDeliveryDates()) !!}">
                            {!! implode(' или ', $next_menu->getDeliveryDates()) !!}</span></span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="{!! $active_basket->getUrl('next') !!}"
                   data-week="1"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif
        </div>

    </section>
@endif