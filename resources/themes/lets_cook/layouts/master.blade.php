@extends('layouts.app')

@section('main')

    <div class="app">

        @include('partials.modules.popup')

        @include('partials.header')

        @yield('content')

        @include('partials.footer')

        @include('partials.modules.messages')

    </div>

@endsection
