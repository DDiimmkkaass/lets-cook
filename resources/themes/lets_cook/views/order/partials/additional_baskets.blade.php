<section class="order__add-more order-add-more">
    <h2 class="order-add-more__title georgia-title">Добавьте к заказу</h2>

    <ul class="order-add-more__list">
        @foreach($additional_baskets as $key => $_basket)
            <li class="order-add-more__item more-item">
                <div class="more-item__img"
                     style="background-image: url({!! thumb($_basket->getImage(), 220, 146) !!});">
                </div>

                <div class="more-item__info" data-device="mobile">
                    <h3 class="more-item__title" data-device="mobile">{!! $_basket->getName() !!}</h3>
                    <div class="more-item__price" data-device="mobile">
                        {!! $_basket->getPrice() !!}<span>{!! $currency !!}</span>
                    </div>
                </div>

                <div class="more-item__info" data-device="desktop">
                    <h3 class="more-item__title" data-device="desktop">{!! $_basket->getName() !!}</h3>

                    <div class="more-item__desc">
                        {!! $_basket->description !!}
                    </div>

                    <div class="more-item__bottom">
                        <div class="more-item__price" data-device="desktop">
                            {!! $_basket->getPrice() !!}<span>{!! $currency !!}</span>
                        </div>

                        <div class="checkbox-button">
                            <input type="checkbox"
                                   id="f-order-add-more-{!! $_basket->id !!}"
                                   data-name="baskets[{!! $key !!}]"
                                   data-price="{!! $_basket->getPrice() !!}"
                                   value="{!! $_basket->id !!}"
                                   class="checkbox-button"
                                   data-id="{!! $_basket->id !!}">
                            <label for="f-order-add-more-{!! $_basket->id !!}" data-add="Добавить"
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