<header class="header">
    @widget__trial_order()

    <div class="header__main header-main">
        <a href="{!! route('home') !!}"
           class="header-main__logo"
           data-page="@if (route_is('home')){!! 'main' !!}@else{!! 'others' !!}@endif">
            <img src="{!! theme_asset('images/main-logo.png') !!}" alt="{!! config('app.name') !!}">
        </a>

        <div class="header-main__wrapper">
            @widget__menu('main')

            @include('partials.contacts')
        </div>
    </div>

    @include('views.auth.popups.login')

    @include('views.auth.popups.register')

    @include('views.auth.popups.restore')
</header>