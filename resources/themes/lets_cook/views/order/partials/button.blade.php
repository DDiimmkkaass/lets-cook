<section class="order__submit order-submit">
    <div class="order-submit__wrapper">
        <div class="order-submit__content">
            <div class="order-submit__common">
                <div class="order-submit__common-title georgia-title">У вас в заказе:</div>

                <ul class="order-submit__list">
                    <li class="order-submit__item">
                        <div class="order-submit__subTitle">{!! $basket->getName() !!}</div>

                        <ul class="order-submit__subList">
                            <li id="recipes_count_result" class="order-submit__subItem">
                                <span>{!! $recipes_count !!}</span>
                                @choice('front_labels.count_of_dinners', $recipes_count)
                            </li>
                            <li id="portions_count_result"  class="order-submit__subItem">
                                <span>{!! $basket->portions !!}</span
                                >@choice('front_labels.count_of_portions', $basket->portions)
                            </li>
                        </ul>
                    </li>

                    @if ($selected_baskets->count())
                        @foreach($selected_baskets as $_basket)
                            <li class="order-submit__item">
                                <div class="order-submit__subTitle">{!! $_basket->getName() !!}</div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div data-total="{!! $basket->getPriceInOrder($recipes_count) !!}"
                 id="order_total_desktop"
                 class="order-submit__price">
                {!! $basket->getPriceInOrder($recipes_count) !!}<span>{!! $currency !!}</span>
            </div>
        </div>

        <button class="order-submit__button" type="submit" name="order-submit">Заказать</button>
    </div>
</section>