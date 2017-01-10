@extends('layouts.app')

@section('main')

    <div class="app simple-page {!! $class or '' !!}">

        @yield('content')

    </div>

@endsection