@extends('layouts.profile')

@section('_content')

    <section class="profile-orders__content profile-orders-content">
        <ul class="profile-orders-content__tabs-list">
            <li class="profile-orders-content__tabs-item" data-tab="{!! $data_tab !!}" data-active>
                <div class="profile-orders-content__tabs-title" data-tab="{!! $data_tab !!}">Мои заказы</div>

                @yield('__content')
            </li>

            <li class="profile-orders-content__tabs-item" data-tab="subscribe">
                <div class="profile-orders-content__tabs-title" data-tab="subscribe">Подписка</div>

                <div class="profile-orders-content__main profile-subscribe" data-tab="subscribe">
                    @include('profile.tabs.subscribe')
                </div>
            </li>

            <li class="profile-orders-content__tabs-item profile-history" data-tab="history">
                <div class="profile-orders-content__tabs-title" data-tab="history">История</div>

                <div class="profile-orders-content__main" data-tab="history">
                    @include('profile.tabs.history')
                </div>
            </li>
        </ul>

        @if ($data_tab == 'my-orders')
            @include('profile.tabs.previous_orders')
        @endif
    </section>

@endsection