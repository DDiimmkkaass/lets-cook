@extends('emails.master')

<?php $coupon = unserialize($coupon); ?>

@section('content')

    <div>

        <div> @lang('front_emails.user registration coupon email message :site_name :discount', [
            'site_name' => config('app.name'),
            'discount' => $coupon->discount,
        ])</div>

        <br/>

        <div><b>@lang('front_labels.code')</b>: {!! $coupon->code !!}</div>
    </div>

@stop