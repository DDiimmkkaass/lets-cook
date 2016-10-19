@if ($banner->visible_items->count())
    <section class="recipe-simple__cook cook-with-us {!! $banner->class !!}">
        <h2 class="cook-with-us__title georgia-title">{!! $banner->name !!}</h2>

        <div class="cook-with-us__desk">
            {!! isset($recipe_name) ? $recipe_name.' и ' : '' !!}300 других рецептов<br/> входят в корзины от "Давай Готовить"
        </div>


            <ul class="cook-with-us__list">
                @foreach($banner->visible_items as $key => $item)
                    <li class="cook-with-us__item">
                        <div class="cook-with-us__item-icon">
                            <img src="{!! thumb($item->image) !!}" alt="Cook with us 1">
                        </div>
                        <div class="cook-with-us__item-desk">{!! $item->text !!}</div>
                    </li>
                @endforeach
            </ul>

        <a href="{!! localize_route('home') !!}" class="cook-with-us__know-more white-long-button">Узнать больше</a>
    </section>
@endif