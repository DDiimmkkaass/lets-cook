@if ($additional_baskets->count())
    <div class="order-edit__add">
        <div class="order-edit__add-title">Добавьте к заказу</div>

        <ul class="order-edit__add-list">
            @if (after_week_closing($weekly_menu->year, $weekly_menu->week))
                @foreach($additional_baskets as $key => $basket)
                    @if ($order->additional_baskets->contains('basket_id', $basket->id))
                        <li class="order-edit__add-item order-add-item">
                            <div class="order-add-item__img"
                                 style="background-image: url({!! thumb($basket->getImage(), 204, 138) !!});"></div>

                            <div class="order-add-item__main">
                                <div class="order-add-item__title">{!! $basket->getName() !!}</div>

                                <div class="order-add-item__bottom">
                                    <div class="order-add-item__checkbox square-red-checkbox">
                                        <input type="checkbox" id="order-add-item-{!! $key !!}"
                                               name="baskets[{!! $key !!}]"
                                               data-price="{!! $basket->price !!}"
                                               checked="checked"
                                               data-_disabled="disabled"
                                               value="{!! $basket->id !!}">
                                        <label for="order-add-item-{!! $key !!}"></label>
                                    </div>

                                    <div class="order-add-item__price">{!! $basket->price !!} {!! $currency !!}</div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            @else
                @foreach($additional_baskets as $key => $basket)
                    <li class="order-edit__add-item order-add-item">
                        <div class="order-add-item__img"
                             style="background-image: url({!! thumb($basket->getImage(), 204, 138) !!});"></div>

                        <div class="order-add-item__main">
                            <div class="order-add-item__title">{!! $basket->getName() !!}</div>

                            <div class="order-add-item__bottom">
                                <div class="order-add-item__checkbox square-red-checkbox">
                                    <input type="checkbox" id="order-add-item-{!! $key !!}"
                                           name="baskets[{!! $key !!}]"
                                           data-price="{!! $basket->price !!}"
                                           @if ($order->additional_baskets->contains('basket_id', $basket->id))
                                           checked="checked"
                                           @endif
                                           value="{!! $basket->id !!}">
                                    <label for="order-add-item-{!! $key !!}"></label>
                                </div>

                                <div class="order-add-item__price">{!! $basket->price !!} {!! $currency !!}</div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
@endif