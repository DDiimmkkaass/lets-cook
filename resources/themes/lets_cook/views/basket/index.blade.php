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
                            data-filter="{!! $basket->portions.'_'.$basket->main_recipes->count() !!}">
                            <div class="baskets-main-item__top" style="background-color: #1b5238;">
                                <div class="baskets-main-item__left">
                                    <h1 class="baskets-main-item__title">
                                        <span data-device="mobile">{!! $basket->getName() !!}</span>
                                        <span data-device="desktop">{!! $basket->getName() !!}</span>
                                    </h1>

                                    <div class="baskets-main-item__result">
                                        <div class="baskets-main-item__result-wrapper">
                                            <ul class="baskets-main-item__count-list">
                                                <li class="baskets-main-item__count-item">
                                                    <span>{!! $basket->main_recipes->count() !!}</span>
                                                    <span>
                                                        @choice('front_labels.count_of_dinners', $basket->main_recipes->count())
                                                    </span>
                                                </li>
                                                <li class="baskets-main-item__count-item">
                                                    <span>{!! $basket->portions !!}</span>
                                                    <span>@choice('front_labels.count_of_portions', $basket->portions)</span>
                                                </li>
                                            </ul>

                                            <div class="baskets-main-item__price" data-device="mobile">
                                                {!! $basket->getOrderPrice() !!}
                                                <span>{!! $currency !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="baskets-main-item__right">
                                    <div class="baskets-main-item__price" data-device="desktop">
                                        {!! $basket->getOrderPrice() !!}
                                        <span>{!! $currency !!}</span>
                                    </div>
                                    <a href="{!! localize_route('order.index', $basket->id) !!}"
                                       class="baskets-main-item__make-order"
                                       data-device="desktop">
                                        Оформить заказ
                                    </a>
                                </div>
                            </div>

                            <div class="baskets-main-item__main">
                                <div class="baskets-main-item__first">
                                    <a href="#" class="baskets-main-item__link">
                                        <div class="baskets-main-item__img"
                                             style="background-image: url({!! thumb($basket->getImage(), 980, 335) !!}); height: 335px"></div>
                                        <h3 class="baskets-main-item__main-title"
                                            style="background-color: #a0152d; color: #ffd8ae;">
                                            {!! $basket->getDescription() !!}
                                        </h3>
                                    </a>
                                </div>

                                @if ($basket->main_recipes->count())
                                    <ul class="baskets-main-item__list">
                                        @foreach($basket->main_recipes as $key => $recipe)
                                            <li class="baskets-main-item__item">
                                                <a href="{!! $recipe->recipe->getUrl() !!}"
                                                   class="baskets-main-item__link">
                                                    <div class="baskets-main-item__img"
                                                         style="background-image: url({!! thumb($recipe->getRecipeImage(), 195, 134) !!});"></div>
                                                    <h3 class="baskets-main-item__item-title"
                                                        style="background-color: #ea9c1f; color: #0a2229;">
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