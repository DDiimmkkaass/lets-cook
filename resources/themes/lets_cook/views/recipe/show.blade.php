@extends('layouts.master')

@section('content')

    <main class="main recipe-simple">
        <section class="recipe-simple__basket recipe-simple-basket">
            <div class="recipe-simple-basket__left">
                <h1 class="recipe-simple-basket__title georgia-title">{!! $model->name !!}</h1>

                <div class="recipe-simple-basket__info">
                    <div class="recipe-simple-basket__details">
                        <div class="recipe-simple-basket__rating">
                            <div class="recipe-simple-basket__rating-title">рейтинг:</div>
                            <div class="recipe-simple-basket__rating-stars">
                                @php ($rating = $model->getRating())
                                @for($i = 1; $i <= 5; $i++)
                                    <span @if ($i <= $rating) data-active @endif></span>
                                @endfor
                            </div>
                        </div>

                        <div class="recipe-simple-basket__count">
                            <div class="recipe-simple-basket__count-title">количество:</div>
                            <div class="recipe-simple-basket__count-value">
                                <span>{!! $model->portions !!}</span>@choice('labels.count_of_portions', $model->portions)
                            </div>
                        </div>

                        <div class="recipe-simple-basket__time">
                            <div class="recipe-simple-basket__time-title">время:</div>
                            <div class="recipe-simple-basket__time-value">{!! $model->cooking_time !!} минут</div>
                        </div>
                    </div>

                    @if ($model->tags->count())
                        <ul class="recipe-simple-basket__tags">
                            @foreach($model->tags as $tag)
                                <li data-tag="{!! $tag->tag->name !!}">{!! $tag->tag->name !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="recipe-simple-basket__right"
                 style="background-image: url({!! thumb($model->image, 720, 370) !!});"></div>
        </section>

        <section class="recipe-simple__all all-baskets">
            <div class="all-baskets__title georgia-title">Корзины</div>

            @if (count($active_baskets))
                <ul class="all-baskets__list">
                    @foreach($active_baskets as $basket)
                        <li>
                            <a href="{!! localize_route('order.index', $basket->id) !!}">{!! $basket->name !!}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @widget__banner('cook_with_us', ['recipe_name' => $model->name])

        <section class="recipe-simple__ingredients order-ing">
            <a href='#' class="order-ing__title ptsans-narrow-regular-tittle">
                <span>Ингредиенты</span>
                <span>Свернуть</span>
            </a>

            <div class="order-ing__lists">
                <div class="order-ing__list-wrapper">
                    <h3 class="order-ing__list-title">Ингредиенты</h3>

                    @if ($model->ingredients->count())
                        <ul class="order-ing__list">
                            @foreach($model->ingredients as $ingredient)
                                <li>{!! $ingredient->ingredient->getTitle() !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="order-ing__list-wrapper">
                    <h3 class="order-ing__list-title">Должно быть на кухне</h3>

                    @if ($model->home_ingredients->count())
                        <ul class="order-ing__list">
                            @foreach($model->home_ingredients as $ingredient)
                                <li>{!! $ingredient->ingredient->getTitle() !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="order-ing__list-wrapper">
                    <h3 class="order-ing__list-title">Требуемый инвентарь</h3>

                    <div class="order-ing__list">
                        {!! $model->home_equipment !!}
                    </div>
                </div>
            </div>

            <div class="order-ing__show black-short-button">
                <span>Развернуть</span>
                <span>Свернуть</span>
            </div>
        </section>

        @if ($model->steps->count())
            <section class="recipe-simple__steps cooking-steps">
                <h2 class="cooking-steps__title georgia-title">Шаги приготовления</h2>

                <ul class="cooking-steps__list">
                    @foreach($model->steps as $key => $step)
                        <li class="cooking-steps__item cook-step">
                            <div class="cook-step__left">
                                <div class="cook-step__number">{!! $key + 1 !!}</div>
                                <div class="cook-step__image"
                                     style="background-image: url({!! thumb($step->image, 261, 146) !!});"></div>
                            </div>

                            <div class="cook-step__right">
                                <div class="cook-step__title">{!! $step->name !!}</div>

                                <div class="cook-step__desk">
                                    {!! $step->description !!}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @widget__subscribe('recipe')

        @if ($model->helpful_hints)
            <section class="recipe-simple__advices useful-advices">
                <h2 class="useful-advices__title">Полезные советы</h2>

                <div class="useful-advices__content">
                    {!! $model->helpful_hints !!}
                </div>
            </section>
        @endif
    </main>

@endsection