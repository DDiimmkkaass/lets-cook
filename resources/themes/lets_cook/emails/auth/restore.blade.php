@extends('emails.master')

@section('content')

    <div>
        @lang('front_emails.password reset email with reset link :link', ['link' => link_to_route('auth.reset', trans('front_labels.reset_password'), ['email' => $email, 'token' => $token])])
    </div>

@stop