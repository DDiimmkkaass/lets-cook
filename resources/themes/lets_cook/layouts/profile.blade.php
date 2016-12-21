@extends('layouts.master')

@section('content')

    <main class="main{!! isset($profile_css_class) ? ' '.$profile_css_class : '' !!}">
        <h1 class="profile-orders__title static-georgia-title">
            @if (isset($page_title)) {!! $page_title !!} @else Личный кабинет @endif
        </h1>

        <section class="profile-orders__user profile-orders-user">
            <div class="profile-orders-user__wrapper">
                <div class="profile-orders-user__mobile">
                    <div class="profile-orders-user__mobile-name">{!! $user->getFullName() !!}</div>

                    <div class="profile-orders-user__mobile-details">
                        @php($success_orders_count = $user_archived_orders->count() + $user->old_site_orders_count)
                        (сделал {!! $success_orders_count !!} @choice('front_labels.user_success_order_count', $success_orders_count))
                    </div>
                </div>

                <ul class="profile-orders-user__desktop">
                    <li class="profile-orders-user__item" data-item="name">
                        <a href="{!! localize_route('profiles.index') !!}"
                           class="profile-orders-user__link">{!! $user->getFullName() !!}</a>
                    </li>

                    <li class="profile-orders-user__item" data-item="my-orders">
                        <a href="{!! localize_route('profiles.orders.index') !!}" class="profile-orders-user__link">Мои заказы</a>
                    </li>

                    <li class="profile-orders-user__item" data-item="orders">
                        Сделано заказов: <span>{!! $success_orders_count !!}</span>
                    </li>

                    <li class="profile-orders-user__item" data-item="exit">
                        <a href="{!! localize_route('auth.logout') !!}" class="profile-orders-user__link">Выход</a>
                    </li>
                </ul>
            </div>
        </section>

        @yield('_content')

    </main>

@endsection

@push('assets.bottom')
    <script type="text/javascript" src="{!! asset('assets/components/clipboard/dist/clipboard.min.js') !!}"></script>
@endpush