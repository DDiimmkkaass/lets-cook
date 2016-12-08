@extends('emails.master')

<?php
$comment = unserialize($comment);
$user = unserialize($user);
?>

@section('content')
    {!! trans('front_emails.new comment :link', ['link' => link_to_route('admin.comment.edit', trans('labels.comment'), ['id' => $comment->id])]) !!}

    <br>

    <div><b>@lang('labels.user'):</b>
        <a title="@lang('labels.go_to_user')" href="{!! route('admin.user.edit', $user->id) !!}">
            {!! $user->getFullName() !!}
        </a>
    </div>
    <div><b>@lang('labels.comment'):</b> {!! $comment->comment !!}</div>
@stop