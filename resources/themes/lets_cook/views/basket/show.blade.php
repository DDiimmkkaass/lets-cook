@extends('layouts.master')

@section('content')

    <main class="main order">
        @include('basket.partials.popup')

        @if ($additional_baskets_tags->count())
            @include('basket.partials.additional_baskets_popup')
        @endif

        @include('basket.partials.steps')

        <form action="{!! localize_route('order.store') !!}" method="post" class="order-create-form">
            {!! csrf_field() !!}

            @include('basket.partials.basket')

            @if ($basket->recipes->count())
                @include('basket.partials.ingredients')
            @endif

            @if ($additional_baskets_tags->count())
                @include('basket.partials.additional_baskets_tags')
            @endif

            @include('basket.partials.delivery')

            @include('basket.partials.payment')

            @include('basket.partials.user_info')

            @if (!$user || ($user && !$user->subscribe()->count()))
                @include('basket.partials.subscribe')
            @endif

            @include('basket.partials.coupon')

            @include('basket.partials.button')

            @widget__banner('what_you_get')
        </form>
    </main>

@endsection