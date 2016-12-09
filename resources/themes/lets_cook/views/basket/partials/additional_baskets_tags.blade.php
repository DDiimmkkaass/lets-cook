<section class="order__add-more order-add-more" data-steps="3">
    <h2 class="order-add-more__title georgia-title">Добавьте к заказу</h2>

    <ul class="order-add-more__hidden">
        @foreach($additional_baskets as $key => $_basket)
            @foreach($_basket->tags as $tag)
                <li class="order-add-more__item more-item" data-more="{!! $tag->tag->id !!}"
                    @if ($selected_baskets->contains('basket_id', $_basket->id)) data-active @endif>
                    <div class="more-item__img"
                         style="background-image: url({!! thumb($_basket->getImage(), 250, 167) !!});">
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
                            {!! $_basket->description !!} {!! $selected_baskets->contains($_basket->id) !!}
                        </div>

                        <div class="more-item__bottom">
                            <div class="more-item__price" data-device="desktop">
                                {!! $_basket->getPrice() !!}<span>{!! $currency !!}</span>
                            </div>

                            <div class="checkbox-button">
                                <input type="checkbox"
                                       id="f-order-add-more-{!! $_basket->id !!}"
                                       data-name="baskets[{!! $key !!}]"
                                       @if ($selected_baskets->contains('basket_id', $_basket->id))
                                       name="baskets[{!! $key !!}]"
                                       checked="checked"
                                       @endif
                                       data-price="{!! $_basket->getPrice() !!}"
                                       value="{!! $_basket->id !!}"
                                       class="checkbox-button f-order-add-more-{!! $_basket->id !!}"
                                       data-basket_name="{!! $_basket->getName() !!}"
                                       data-id="{!! $_basket->id !!}">
                                <label for="f-order-add-more-{!! $_basket->id !!}" data-add="Добавить"
                                       data-remove="Убрать">
                                    @if ($selected_baskets->contains('basket_id', $_basket->id)) Убрать @else
                                        Добавить @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        @endforeach
    </ul>

    <ul class="order-add-more__list">
        @foreach($additional_baskets_tags as $tag)
            <li class="order-add-more__item more-item" data-more="{!! $tag['tag']->id !!}">
                <div class="more-item__img"
                     style="background-image: url({!! thumb($tag['tag']->image, 220, 146) !!});"></div>

                <div class="more-item__info" data-device="mobile">
                    <h3 class="more-item__title" data-device="mobile">{!! $tag['tag']->name !!}</h3>
                    <div class="more-item__price" data-device="mobile">
                        {!! $tag['price'] !!}
                        <span>{!! $currency !!}</span>
                    </div>
                </div>

                <div class="more-item__info" data-device="desktop">
                    <h3 class="more-item__title" data-device="desktop">{!! $tag['tag']->name !!}</h3>

                    <div class="more-item__bottom">
                        <div class="more-item__price" data-device="desktop">
                            <span data-span="1">от</span>{!! $tag['price'] !!}
                            <span data-span="2">{!! $currency !!}</span></div>

                        <div class="more-item__button">Выбрать</div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</section>