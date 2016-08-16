@extends('emails.master')

@php($order = unserialize($order))

@section('content')
    @lang('messages.tmpl order successfully paid message :order_id', ['order_id' => $order->id])
@endsection