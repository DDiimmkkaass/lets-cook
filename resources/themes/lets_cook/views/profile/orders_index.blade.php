@extends('layouts.master')

@section('content')
    <main class="main profile-orders">
        <h1 class="profile-orders__title static-georgia-title">Личный кабинет</h1>

        <section class="profile-orders__user profile-orders-user">
            <div class="profile-orders-user__wrapper">
                <div class="profile-orders-user__mobile">
                    <div class="profile-orders-user__mobile-name">{!! $user->getFullName() !!}</div>

                    <div class="profile-orders-user__mobile-details">(сделал {!! $history_orders->count() !!} заказа)</div>
                </div>

                <ul class="profile-orders-user__desktop">
                    <li class="profile-orders-user__item" data-item="name">
                        <a href="#" class="profile-orders-user__link">{!! $user->getFullName() !!}</a>
                    </li>

                    <li class="profile-orders-user__item" data-item="orders">Сделано заказов: <span>{!! $history_orders->count() !!}</span></li>

                    <li class="profile-orders-user__item" data-item="exit">
                        <a href="{!! localize_route('auth.logout') !!}" class="profile-orders-user__link">Выход</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="profile-orders__content profile-orders-content">
            <ul class="profile-orders-content__tabs-list">
                <li class="profile-orders-content__tabs-item" data-tab="my-orders" data-active>
                    <div class="profile-orders-content__tabs-title" data-tab="my-orders">Мои заказы</div>

                    <div class="profile-orders-content__main profile-orders-own content-tab" data-tab="my-orders">
                        @include('profile.tabs.orders')
                    </div>
                </li>

                <li class="profile-orders-content__tabs-item" data-tab="subscribe">
                    <div class="profile-orders-content__tabs-title" data-tab="subscribe">Подписка</div>

                    <div class="profile-orders-content__main profile-subscribe content-tab" data-tab="subscribe">
                        @include('profile.tabs.subscribe')
                    </div>
                </li>

                <li class="profile-orders-content__tabs-item profile-history" data-tab="history">
                    <div class="profile-orders-content__tabs-title" data-tab="history">История</div>

                    <div class="profile-orders-content__main content-tab" data-tab="history">
                        @include('profile.tabs.history')
                    </div>
                </li>
            </ul>

            @include('profile.tabs.previous_orders')
        </section>
    </main>
@endsection