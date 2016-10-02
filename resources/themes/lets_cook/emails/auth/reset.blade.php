@extends('emails.master')

@section('content')

    <div>
        <div> @lang('front_messages.congratulations, you have successfully reset your password')</div>
        <br />

        <div>@lang('front_messages.to login in your account use next data'): </div>
        <br />

        <div><b>@lang('front_labels.email')</b>: {!! $email  !!}</div>
        <br />
        <div><b>@lang('front_labels.password')</b>: {!! $password !!}</div>
    </div>

@stop