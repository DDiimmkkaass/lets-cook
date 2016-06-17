@extends('layouts.main')

@section('assets.top')
    @parent

    <link rel="stylesheet" href="{!! asset('assets/components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') !!}"/>

@endsection

@section('assets.bottom')
    @parent

    <script src="{!! asset('assets/components/bootstrap-switch/dist/js/bootstrap-switch.min.js') !!}"></script>

@endsection