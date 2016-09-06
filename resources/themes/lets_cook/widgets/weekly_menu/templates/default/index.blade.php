<div class="recipes-and-baskets__item recipes-menu">
    <ul class="recipes-menu__choose">
        @if ($menu)
            <li class="recipes-menu__chooseItem">Меню на эту неделю</li>
        @endif
        @if ($next_menu)
            <li class="recipes-menu__chooseItem">Меню на следующую неделю</li>
        @endif
    </ul>

    <ul class="recipes-menu__content">
        @if ($menu)
            @if ($menu->main_recipes->count())
                <li class="recipes-menu__contentItem">
                    <ul class="recipes-menu__list">
                        @foreach($menu->main_recipes as $recipe)
                            <li class="recipes-menu__item">
                                <a href="{!! $recipe->recipe->getUrl() !!}"
                                   title="{!! $recipe->getRecipeName() !!}"
                                   class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url({!! thumb($recipe->recipe->image) !!});">
                                    </div>
                                    <h3 class="recipes-menu__title">
                                        {!! $recipe->getRecipeName() !!}
                                    </h3>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{!! $menu->getUrl() !!}" class="recipes-menu__all yellow-small-button">Все рецепты</a>
                </li>
            @endif
        @endif

        @if ($next_menu)
            @if ($next_menu->main_recipes->count())
                <li class="recipes-menu__contentItem">
                    <ul class="recipes-menu__list">
                        @foreach($next_menu->main_recipes as $recipe)
                            <li class="recipes-menu__item">
                                <a href="{!! $recipe->recipe->getUrl() !!}"
                                   title="{!! $recipe->getRecipeName() !!}"
                                   class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url({!! thumb($recipe->recipe->image) !!});">
                                    </div>
                                    <h3 class="recipes-menu__title">
                                        {!! $recipe->getRecipeName() !!}
                                    </h3>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{!! $next_menu->getUrl() !!}" class="recipes-menu__all yellow-small-button">Все рецепты</a>
                </li>
            @endif
        @endif
    </ul>
</div>