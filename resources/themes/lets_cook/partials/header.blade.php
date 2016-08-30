<header class="header">
    <div class="header__top header-top" data-active="true">
        <a href="#" class="header-top__link">
            Закажите пробный <span>ужин на двоих за 600 рублей</span>
        </a>
        <a href="#" class="header-top__close">Закрыть</a>
    </div>

    <div class="header__main header-main">
        <a href="{!! route('home') !!}" class="header-main__logo" data-page="main">
            <img src="{!! theme_asset('images/main-logo.png') !!}" alt="{!! config('app.name') !!}">
        </a>

        <div class="header-main__wrapper">
            @widget__menu('main')

            @include('partials.contacts')
        </div>
    </div>

    @include('views.auth.popups.login')

    @include('views.auth.popups.register')
</header>