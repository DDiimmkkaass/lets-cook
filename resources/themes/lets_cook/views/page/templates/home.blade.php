@extends('layouts.master')

@section('content')
    <main class="main">
        <section class="let-cook">
            <h3 class="let-cook__supTitle">Сервис доставки качественных продуктов на дом</h3>
            <h1 class="let-cook__title">&laquo; {!! config('app.name') !!} &raquo;</h1>
            <h2 class="let-cook__subTitle">просто и вкусно</h2>

            <div class="make-order--desktop">
                <a href="{!! localize_route('baskets.index', 'current') !!}" class="choose-basket green-long-button">Выбрать
                    корзину</a>

                @widget__trial_order('home')
            </div>
        </section>

        <section class="make-order--mobile">
            <a href="{!! localize_route('baskets.index', 'current') !!}" class="choose-basket">Выбрать корзину</a>

            @widget__trial_order('home')
        </section>

        @widget__banner('what_we_offer')

        @if (variable('video_on_home'))
            <section class="about-service">
                <div class="about-service__title">
                    <div class="about-service__icon"></div>
                    <div class="about-service__text">Основатель о том, как работает сервис</div>
                </div>

                <div class="about-service__content">
                    <div class="about-service__close"></div>

                    <div class="about-service__video">
                        <iframe src="https://www.youtube.com/embed/{!! variable('video_on_home') !!}?autoplay=0&showinfo=0&controls=0&enablejsapi=1"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </section>
        @endif

        @widget__weekly_menu()

        @widget__banner('what_makes_us_different')

        <section class="free-delivery">
            <h2 class="free-delivery__title">доставка бесплатно</h2>
            <div class="free-delivery__subTitle">c воскресенья по понедельник, и до 12 км от МКАД</div>
            <div class="free-delivery__desc">Доставка на большие расстояния стоит 24 рубля за каждый километр от МКАД
            </div>
        </section>

        <section class="spec-review">
            <h2 class="spec-review__title">диетолог о «Давай готовить!»</h2>

            <div class="spec-review__main">
                <div class="spec-review__img" style="background-image: url('/assets/themes/lets_cook/images/spec-review-1.jpg')"></div>

                <div class="spec-review__comment">
                    <div class="spec-review__inner">
                        <div class="spec-review__toggle">
                            <span class="spec-review__show">Подробнее</span>
                            <span class="spec-review__hide">Закрыть</span>
                        </div>

                        <div class="spec-review__text">
                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>

                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>

                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>
                        </div>
                    </div>

                    <div class="spec-review__hois">
                        <div class="spec-review__name">Людмила Денисенко</div>

                        <div class="spec-review__profession">(диетолог, «Азбука стройности»)</div>
                    </div>
                </div>
            </div>
        </section>

        @widget__random_comments(4)

        <section class="all-news-articles">
            @widget__last_news('one_item', 1)

            @widget__last_articles('one_item', 1)
        </section>

        @widget__subscribe()
    </main>
@endsection