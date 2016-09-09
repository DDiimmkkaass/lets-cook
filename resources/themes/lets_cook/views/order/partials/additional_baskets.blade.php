<section class="order__add-more order-add-more">
    <h2 class="order-add-more__title georgia-title">Добавьте к заказу</h2>

    <ul class="order-add-more__list">
        @foreach($additional_baskets as $key => $basket)
            <li class="order-add-more__item more-item">
                <div class="more-item__img"
                     style="background-image: url({!! thumb($basket->getImage(), 220, 146) !!});">
                </div>

                <div class="more-item__info" data-device="mobile">
                    <h3 class="more-item__title" data-device="mobile">{!! $basket->getName() !!}</h3>
                    <div class="more-item__price" data-device="mobile">
                        {!! $basket->getPrice() !!}<span>{!! $currency !!}</span>
                    </div>
                </div>

                <div class="more-item__info" data-device="desktop">
                    <h3 class="more-item__title" data-device="desktop">{!! $basket->getName() !!}</h3>

                    <div class="more-item__desc">
                        {!! $basket->description !!}
                    </div>

                    <div class="more-item__bottom">
                        <div class="more-item__price" data-device="desktop">
                            {!! $basket->getPrice() !!}<span>{!! $currency !!}</span>
                        </div>

                        <div class="checkbox-button">
                            <input type="checkbox"
                                   id="f-order-add-more-{!! $basket->id !!}"
                                   data-name="baskets[{!! $key !!}]"
                                   value="{!! $basket->id !!}"
                                   class="checkbox-button"
                                   data-id="{!! $basket->id !!}">
                            <label for="f-order-add-more-{!! $basket->id !!}" data-add="Добавить"
                                   data-remove="Убрать">
                                Добавить
                            </label>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</section>