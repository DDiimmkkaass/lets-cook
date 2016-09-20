@extends('layouts.master')

@section('content')

    <main class="main baskets">
        <section class="baskets__top baskets-top">
            <h1 class="baskets-top__title georgia-title">Закажите корзину</h1>
        </section>

        @if (count($baskets))
            @include('basket.partials.filters')

            <section class="baskets__main baskets-main">
                <ul class="baskets-main__list">
                    @foreach($baskets as $basket)
                        <li class="baskets-main__item baskets-main-item"
                            data-filter="{!! $basket->portions.'_'.$basket->recipes->count() !!}">
                            <div class="baskets-main-item__top">
                                <div class="baskets-main-item__left">
                                    <h1 class="baskets-main-item__title">
                                        <span data-device="mobile">{!! $basket->getName() !!}</span>
                                        <span data-device="desktop">{!! $basket->getName() !!}</span>
                                    </h1>

                                    <div class="baskets-main-item__result">
                                        <div class="baskets-main-item__result-wrapper">
                                            <ul class="baskets-main-item__count-list">
                                                <li class="baskets-main-item__count-item">
                                                    <span>{!! $basket->recipes->count() !!}</span>
                                                    <span>
                                                        @choice('front_labels.count_of_dinners', $basket->recipes->count())
                                                    </span>
                                                </li>
                                                <li class="baskets-main-item__count-item">
                                                    <span>{!! $basket->portions !!}</span>
                                                    <span>@choice('front_labels.count_of_portions', $basket->portions)</span>
                                                </li>
                                            </ul>

                                            <div class="baskets-main-item__price" data-device="mobile">
                                                {!! $basket->getPriceInOrder() !!}
                                                <span>{!! $currency !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="baskets-main-item__right">
                                    <div class="baskets-main-item__price" data-device="desktop">
                                        {!! $basket->getPriceInOrder() !!}
                                        <span>{!! $currency !!}</span>
                                    </div>
                                    @if (empty($next))
                                        <a href="{!! localize_route('order.index', $basket->id) !!}"
                                           class="baskets-main-item__make-order"
                                           data-device="desktop">
                                            Оформить заказ
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="baskets-main-item__main">
                                <div class="baskets-main-item__first">
                                    <a href="#" class="baskets-main-item__link">
                                        <div class="baskets-main-item__img"
                                             style="background-image: url({!! thumb($basket->getImage(), 980, 335) !!}); height: 335px"></div>
                                        <h3 class="baskets-main-item__main-title">
                                            {!! $basket->getDescription() !!}
                                        </h3>
                                    </a>
                                </div>

                                @if ($basket->recipes->count())
                                    <ul class="baskets-main-item__list">
                                        @foreach($basket->recipes as $key => $recipe)
                                            <li class="baskets-main-item__item">
                                                <a href="{!! $recipe->recipe->getUrl() !!}"
                                                   class="baskets-main-item__link">
                                                    <div class="baskets-main-item__img"
                                                         style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 134) !!});"></div>
                                                    <h3 class="baskets-main-item__item-title">
                                                        @choice('front_labels.day_with_number', $key + 1)
                                                        : {!! $recipe->getRecipeName() !!}
                                                    </h3>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </main>

@endsection