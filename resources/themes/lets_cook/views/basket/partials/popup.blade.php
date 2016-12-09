<div class="order__pop-up order-pop-up">
    <div class="order-pop-up__wrapper">
        <div class="order-pop-up__title">{!! $basket->getName() !!}</div>

        <ul class="order-pop-up__list">
            @foreach($basket->recipes->keyBy('position') as $key => $recipe)
                <li class="order-pop-up__item order-day-item">
                    <div class="order-day-item__img" style="background-image: url({!! thumb($recipe->getRecipeImage(), 270, 180) !!})"></div>

                    <div class="order-day-item__content">
                        <div class="order-day-item__title">@choice('front_labels.day_with_number', $key): {!! $recipe->getRecipeName() !!}</div>

                        <div class="order-day-item__buttons" data-index="{!! $key !!}">
                            <div class="order-day-item__add-remove">
                                <input type="checkbox"
                                       id="f-order-day-add-remove-{!! $key !!}"
                                       class="order-day-item__add-remove-checkbox"
                                       name="order-day-add-remove">

                                <label for="f-order-day-add-remove-{!! $key !!}"
                                       class="order-day-item__add-remove-label black-short-button">
                                    <span>Добавить</span>
                                    <span>Удалить</span>
                                </label>

                                <div class="order-day-item__basket">В корзине</div>
                            </div>

                            <div class="order-day-item__edit black-short-button" data-count="1"><span>1 ужин</span></div>
                        </div>
                    </div>

                    <div class="order-day-item__change order-day-change">
                        <div class="order-day-change__title">ужинов</div>

                        <div class="order-day-change__cancel"></div>

                        <ul class="order-day-change__list">
                            @for ($i = 1; $i <= 7; $i++)
                                <li class="order-day-change__item">
                                    <input type="radio" id="f-order-day-change-{!! $key !!}-{!! $i !!}"
                                           name="order-day-add-change" data-number="{!! $i !!}" data-title="{!! $i !!} @choice('front_labels.count_of_dinners', $i)">
                                    <label for="f-order-day-change-{!! $key !!}-{!! $i !!}">{!! $i !!}</label>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="order-pop-up__bottom order-pop-up-bottom">
            <div class="order-pop-up-bottom__left">
                <div class="order-pop-up-bottom__info">Ужинов:<span id="total_dinners">0</span></div>

                <div class="order-pop-up-bottom__info">Общая стоимость:<span id="popup_total_price">0</span> {!! $currency !!}</div>
            </div>

            <div class="order-pop-up-bottom__right">
                <div class="order-pop-up-bottom__cancel yellow-short-button">Отменить</div>
                <div class="order-pop-up-bottom__save red-short-button">Сохранить</div>
            </div>
        </div>
    </div>

    <div class="order-pop-up__bg-layout"></div>
</div>