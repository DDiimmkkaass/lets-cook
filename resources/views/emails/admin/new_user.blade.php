@extends('emails.master')

<?php $user = unserialize($user); ?>

@section('content')

    <div>
        <div> @lang('front_emails.new user admin message :site_name', ['site_name' => config('app.name')])</div>
        <br/>

        <h3>@lang('front_labels.user_info')</h3>
        <div>
            <div><b>@lang('front_labels.full_name')</b>: {!! $user->getFullName()  !!}</div>
            <div><b>@lang('front_labels.email')</b>: {!! $user->email  !!}</div>
            <div><b>@lang('front_labels.phone')</b>: {!! $user->phone  !!}</div>
            @if ($user->additional_phone)
                <div><b>@lang('front_labels.phone')</b>: {!! $user->additional_phone !!}</div>
            @endif
            <b>@lang('front_labels.address')</b>: {!! $user->getFullAddress()  !!}
        </div>
    </div>

@stop