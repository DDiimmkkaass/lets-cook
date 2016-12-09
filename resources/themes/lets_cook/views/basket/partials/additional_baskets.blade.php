<section class="baskets__add-more order-add-more-info">
    <h2 class="order-add-more-info__title georgia-title">@lang('front_labels.add_to_order')</h2>

    @foreach($additional_baskets_tags as $tag)
        @if (count($tag['baskets']))
            <div class="order-add-more-info__wrapper">
                <div class="order-add-more-info__subTitle">{!! $tag['name'] !!}</div>

                <ul class="order-add-more-info__list">
                    @foreach($tag['baskets'] as $additional_basket)
                        <li class="order-add-more-info__item more-item-info">
                            <div class="more-item-info__img"
                                 style="background-image: url({!! thumb($additional_basket->image, 220, 146) !!});">
                            </div>

                            <div class="more-item-info__info" data-device="mobile">
                                <h3 class="more-item-info__title"
                                    data-device="mobile">{!! $additional_basket->getName() !!}</h3>
                                <div class="more-item-info__price" data-device="mobile">
                                    {!! $additional_basket->getPrice() !!}<span>{!! $currency !!}</span>
                                </div>
                            </div>

                            <div class="more-item-info__info" data-device="desktop">
                                <h3 class="more-item-info__title"
                                    data-device="desktop">{!! $additional_basket->getName() !!}</h3>

                                <div class="more-item-info__desc">{!! $additional_basket->getDescription() !!}</div>

                                <div class="more-item-info__bottom">
                                    <div class="more-item-info__price" data-device="desktop">
                                        {!! $additional_basket->getPrice() !!}<span>{!! $currency !!}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach

</section>