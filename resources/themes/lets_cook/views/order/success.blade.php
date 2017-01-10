@extends('layouts.simple')

@section('content')

    <section class="main">
        <input id="redirect_url" type="hidden" value="{!! $redirect_url !!}">

        <h1 class="h-main-title">
            @lang('front_labels.thanks_for_your_order')
        </h1>

        <div class="order-info">
            <div class="info-row">
                <b>@lang('front_labels.you_order_as_action'):</b> {!! $order->getMainBasketName() !!}
            </div>
            @if ($order->additional_baskets->count())
                <div class="info-row">
                    <b>@lang('front_labels.additional_baskets'):</b> {!! $order->getAdditionalBasketsList() !!}
                </div>
            @endif
            @if ($order->ingredients->count())
                <div class="info-row">
                    <b>@lang('front_labels.additional_ingredients'):</b> {!! $order->getIngredientsList() !!}
                </div>
            @endif
            <div class="info-row">
                <b>@lang('front_labels.order_total'):</b>&nbsp;
                <span id="order_total">{!! $order->total !!}</span> {!! $currency !!}
            </div>
            @if ($order->coupon_id)
                <div class="info-row">
                    <b>@lang('front_labels.used_coupon'):</b>&nbsp;
                    {!! $order->getCouponCode() !!}&nbsp;
                    (<span class="h-text-lowercase">@lang('front_labels.discount')</span>:&nbsp;
                    {!! $order->getDiscount() !!} {!! $currency !!})
                </div>
            @endif
            <div class="info-row">
                <b>@lang('front_labels.delivery_address'):</b>&nbsp;{!! $order->getFullAddress() !!}
            </div>
            <div class="info-row">
                <b>@lang('front_labels.delivery_time'):</b>&nbsp;{!! $order->getFormattedDeliveryDate() !!}
            </div>
        </div>

    </section>

@endsection