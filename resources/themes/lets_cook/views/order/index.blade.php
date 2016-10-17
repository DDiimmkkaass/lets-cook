@extends('layouts.master')

@section('content')

    <main class="main order">
        @include('order.partials.popup')

        @if ($additional_baskets_tags->count())
            @include('order.partials.additional_baskets_popup')
        @endif

        @include('order.partials.steps')

        <form action="{!! localize_route('order.store') !!}" method="post" class="order-create-form">
            {!! csrf_field() !!}

            @include('order.partials.basket')

            @if ($basket->recipes->count())
                @include('order.partials.ingredients')
            @endif

            @if ($additional_baskets_tags->count())
                @include('order.partials.additional_baskets_tags')
            @endif

            @include('order.partials.delivery')

            @include('order.partials.payment')

            @include('order.partials.user_info')

            @if (!$user || ($user && !$user->subscribe()->count()))
                @include('order.partials.subscribe')
            @endif

            @include('order.partials.coupon')

            @include('order.partials.button')

            @widget__banner('what_you_get')
        </form>
    </main>

@endsection