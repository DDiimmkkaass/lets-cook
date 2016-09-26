@extends('emails.master')

<?php $coupon = unserialize($coupon); ?>

@section('content')

    <div>
        <div> @lang('front_messages.tanks for registration on site :site_name, this is you registration coupon :discount', ['site_name' => config('app.name'), 'discount' => $coupon->discount])</div>
        <br />

        <div><b>@lang('front_labels.code')</b>: {!! $coupon->code !!}</div>
    </div>

@stop