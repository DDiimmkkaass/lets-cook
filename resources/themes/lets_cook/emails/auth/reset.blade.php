@extends('emails.master')

@section('content')

    <div>
        <div> @lang('front_emails.success password reset message')</div>
        <br />

        <div><b>@lang('front_labels.email')</b>: {!! $email  !!}</div>
        <br />
        <div><b>@lang('front_labels.password')</b>: {!! $password !!}</div>
    </div>

@stop