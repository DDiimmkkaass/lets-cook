@extends('layouts.simple')

@section('content')

    <section class="main">
        <h2 class="let-cook__subTitle">
            @lang('front_texts.basket not available on this week')
        </h2>

        <a class="black-long-button"
           title="@lang('front_labels.go_to_baskets')"
           href="{!! localize_route('baskets.index', 'current') !!}">
            @lang('front_labels.our_baskets')
        </a>
    </section>

@endsection