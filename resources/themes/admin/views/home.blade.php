@extends('layouts.main')

@section('content')

    @include('order.partials.summary_table', ['home' => true])

@stop