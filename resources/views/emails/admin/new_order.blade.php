@extends('emails.master')

<?php $order = unserialize($order); ?>

@section('content')

    <div>
        <div> @lang('front_emails.new order admin message :site_name', ['site_name' => config('app.name')])</div>

        <h3>@lang('front_labels.user_info')</h3>
        <div>
            <div><b>@lang('front_labels.full_name')</b>: {!! $order->getUserFullName()  !!}</div>
            <div><b>@lang('front_labels.email')</b>: {!! $order->email  !!}</div>
            <div><b>@lang('front_labels.phone')</b>: {!! $order->phone  !!}</div>
        </div>
        <br>

        <div>
            <div>
                <b>@lang('front_labels.date')</b>: {!! $order->getFormattedDeliveryDate() !!}
                <b>@lang('front_labels.address')</b>: {!! $order->getFullAddress()  !!}
                @if ($order->comment)
                    <p>{!! $order->commnet !!}</p>
                @endif
            </div>
        </div>

        <div>
            <div>
                <b>@lang('front_labels.payment_method')</b>:
                @lang('front_labels.payment_method_'.$order->getStringPaymentMethod())
            </div>
        </div>
        <br>

        <h3>@lang('front_labels.order_list')</h3>
        <div>
            <b>@lang('front_labels.main_basket'):</b> {!! $order->main_basket->getName() !!}
            ({!! $order->main_basket->getPortions() !!} @choice('front_labels.count_of_portions', $order->main_basket->getPortions()))
            - {!! $order->main_basket->price !!} {!! $currency !!}
        </div>

        <h3>@lang('front_labels.recipes'): </h3>
        @foreach($order->recipes as $recipe)
            <div>{!! $recipe->getRecipeName() !!}</div>
        @endforeach

        @if ($order->ingredients->count())
            <h3>@lang('front_labels.ingredients'): </h3>
            @foreach($order->ingredients as $ingredient)
                <div>
                    {!! $ingredient->getName() !!} -
                    {!! $ingredient->count !!} {!! $ingredient->getSaleUnit() !!} -
                    {!! $ingredient->count * $ingredient->price !!} {!! $currency !!}
                </div>
            @endforeach
        @endif

        <h3>@lang('front_labels.must_be_at_home'): </h3>
        @php($i = 0)
        @php($viewed = [])
        @foreach($order->recipes as $recipe)
            @foreach($recipe->recipe->recipe->home_ingredients as $ingredient)
                @if (! in_array($ingredient->ingredient_id, $viewed))
                    @php($viewed[] = $ingredient->ingredient_id)
                    <div>{!! $ingredient->ingredient->getTitle() !!}</div>
                    @php($i++)
                @endif
            @endforeach
        @endforeach

        @if ($order->additional_baskets->count())
            <h3>@lang('front_labels.additional_baskets') </h3>
            @foreach($order->additional_baskets as $baskets)
                <div>
                    {!! $baskets->getName() !!} - {!! $baskets->price !!} {!! $currency !!}
                </div>
            @endforeach
        @endif

        @if (!empty($order->coupon_id))
            <br/>
            <div>@lang('front_labels.used_coupon'): <b>{!! $order->coupon->name !!}</b>
                ({!! $order->coupon->code !!})
            </div>

            <b>@lang('front_labels.discount'):</b> {!! $order->subtotal - $order->total !!} {!! $currency !!}
            <br/>
        @endif

        <h2>
            <b>@lang('front_labels.order_total'):</b> {!! $order->total !!} {!! $currency !!}
        </h2>
    </div>

@stop