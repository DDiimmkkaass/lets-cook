@extends('emails.master')

<?php $user = unserialize($user); ?>

@section('content')

    <div>
        <div> @lang('front_messages.congratulations wit registration on site :site_name', ['site_name' => config('app.name')])</div>
        <br />

        <div>@lang('front_messages.to login in your account use next data'): </div>
        <br />

        <div><b>@lang('front_labels.email')</b>: {!! $user->email  !!}</div>
        <br />
        <div><b>@lang('front_labels.password')</b>: {!! $password !!}</div>
    </div>

@stop