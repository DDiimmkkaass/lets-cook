    @extends('layouts.master')

@section('content')
    <main class="main">
        <section class="let-cook" @if (variable('home_main_image')) style="background: url({!! variable('home_main_image') !!}) center center no-repeat" @endif>
            <h3 class="let-cook__supTitle">
                @lang('front_texts.home top text 1')
            </h3>
            <h1 class="let-cook__title">&laquo; {!! config('app.name') !!} &raquo;</h1>
            <h2 class="let-cook__subTitle">
                @lang('front_texts.home top text 2')
            </h2>

            <div class="make-order--desktop">
                <a href="#" class="choose-basket go-to-choose-basket green-long-button">
                    @lang('front_labels.select_basket')
                </a>

                @widget__trial_order('home')
            </div>
        </section>

        <section class="make-order--mobile">
            <a href="{!! localize_route('baskets.index', 'current') !!}" class="choose-basket">
                @lang('front_labels.select_basket')
            </a>

            @widget__trial_order('home')
        </section>

        @widget__banner('what_we_offer')

        @if (variable('video_on_home'))
            <section class="about-service">
                <div class="about-service__title">
                    <div class="about-service__icon"></div>
                    <div class="about-service__text">@lang('front_texts.video on home title')</div>
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
            <h2 class="free-delivery__title">@lang('front_texts.delivery text title')</h2>
            <div class="free-delivery__subTitle">@lang('front_texts.delivery text subtitle')</div>
            <div class="free-delivery__desc">@lang('front_texts.delivery text description')</div>
        </section>

        <section class="spec-review">
            <h2 class="spec-review__title">диетолог о «{!! config('app.name') !!}»</h2>

            <div class="spec-review__main">
                <div class="spec-review__img" style="background-image: url({!! thumb(variable('nutritionist_image')) !!})"></div>

                <div class="spec-review__comment">
                    <div class="spec-review__inner">
                        <div class="spec-review__toggle">
                            <span class="spec-review__show">Подробнее</span>
                            <span class="spec-review__hide">Закрыть</span>
                        </div>

                        <div class="spec-review__text">
                            <div class="spec-review__text_text">
                                <p>
                                    @lang('front_texts.home nutritionist text')
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="spec-review__hois">
                        <div class="spec-review__name">@lang('front_texts.nutritionist name')</div>

                        <div class="spec-review__profession">@lang('front_texts.nutritionist description')</div>
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