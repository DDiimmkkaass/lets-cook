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
                        @endif
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="0" data-basket="{!! $key !!}">
                                <a href="{!! localize_route('order.index', $basket->id) !!}"
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

                @if ($next_menu)
                    @foreach($next_menu_baskets as $key => $basket)
                        @if ($key == 0 && !isset($active_basket))
                            @php($active_basket = $basket)
                        @endif
                        @foreach($basket->recipes as $_key => $recipe)
                            <li class="recipes-menu__item" data-week="1" data-basket="{!! $key !!}">
                                <a href="{!! localize_route('order.index', $basket->id) !!}"
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

                <a href="{!! isset($active_basket) ? localize_route('order.index', $active_basket->id) : '#' !!}" class="recipes-menu__all yellow-small-button">Все рецепты</a>
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
                                    data-url="{!! localize_route('order.index', $basket->id) !!}"
                                    @if ($key == 0) data-active-item @endif
                                    data-week="0"
                                    data-basket="{!! $key !!}">
                                    {!! $basket->getName() !!}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                @if ($next_menu)
                    <li class="baskets-menu__main-item" data-week="1">
                        <ul class="baskets-menu__sub-list">
                            @foreach($next_menu_baskets as $key => $basket)
                                <li class="baskets-menu__sub-item"
                                    data-url="{!! localize_route('order.index', $basket->id) !!}"
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
                    <span>получите заказ {!! implode(' или ', $menu->getDeliveryDates()) !!}</span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="{!! localize_route('order.index', $active_basket->id) !!}"
                   data-week="0"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif

            @if ($next_menu)
                <div class="baskets-menu__desc" data-week="1">
                    Оформите сегодня, и вы
                    <span>получите заказ {!! implode(' или ', $next_menu->getDeliveryDates()) !!}</span>.<br/>
                    Заказы на это меню принимаются до
                    <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="{!! localize_route('order.index', $active_basket->id) !!}"
                   data-week="1"
                   class="baskets-menu__details black-long-button baskets-menu__to-order">
                    подробнее про корзину
                </a>
            @endif
        </div>

    </section>
@endif