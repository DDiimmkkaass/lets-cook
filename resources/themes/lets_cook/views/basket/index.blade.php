@extends('layouts.master')

@section('content')

    <main class="main baskets">
        <section class="baskets__top baskets-top">
            <h1 class="baskets-top__title georgia-title">Закажите корзину</h1>
        </section>

        @if (count($baskets))
            <section class="baskets__main baskets-main">
                <ul class="baskets-main__list">
                    @if ($new_year_basket)
                        @include('basket.partials.index_basket', ['basket' => $new_year_basket])
                    @endif

                    @foreach($baskets as $basket)
                        @include('basket.partials.index_basket')
                    @endforeach
                </ul>
            </section>
        @endif

        @include('basket.partials.additional_baskets')
    </main>

@endsection