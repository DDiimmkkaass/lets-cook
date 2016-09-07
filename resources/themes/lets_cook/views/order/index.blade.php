@extends('layouts.master')

@section('content')

    <main class="main order">
        @include('order.partials.basket')

        @if ($basket->main_recipes->count())
            @include('order.partials.ingredients')
        @endif

        @if ($additional_baskets->count())
            @include('order.partials.additional_baskets')
        @endif

        @include('order.partials.delivery')

        @include('order.partials.payment')

        @include('order.partials.subscribe')

        @include('order.partials.button')

        @widget__banner('what_you_get')
    </main>

@endsection