@extends('emails.master')

@php($order = unserialize($order))

@section('content')
    @lang('messages.tmpl order payment error message :order_id', ['order_id' => $order->id])

    <div>
        @lang('labels.message'): {!! $_message !!}
    </div>
@endsection