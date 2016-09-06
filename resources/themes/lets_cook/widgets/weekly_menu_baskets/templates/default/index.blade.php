@if ($menu)
    <div class="recipes-and-baskets__item baskets-menu">
        <h2 class="baskets-menu__title">наши корзины</h2>

        @if ($menu->baskets->count())
            <table class="baskets-menu__list">
                <tbody>

                @foreach($menu->baskets as $basket)
                    <tr>
                        <td><a href="#">{!! $basket->getName() !!}</a></td>
                        <td>
                        <span>
                            {!! $basket->main_recipes->count() !!}
                        </span> @choice('front_labels.count_of_dinners', $basket->main_recipes->count())
                        </td>
                        <td>
                            <span>{!! $basket->portions !!}</span> @choice('front_labels.count_of_portions', $basket->portions)
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif

        <div class="baskets-menu__desc">
            Оформите сегодня, и вы <span>получите заказ {!! implode(' или ', $menu->getDeliveryDates()) !!}</span>.<br/>
            Заказы на это меню принимаются до <span>{!! variable('stop_ordering_time') !!} {!! trans_choice('front_labels.day_of_week_to_string_when', variable('stop_ordering_date')) !!}</span>.<br/>
            Заказав после, вы получите продукты меню следующей недели.
        </div>

        <a href="{!! localize_route('baskets.index') !!}"
           title="Подробнее про корзины"
           class="baskets-menu__details black-long-button">
            Подробнее про корзины
        </a>
    </div>
@endif