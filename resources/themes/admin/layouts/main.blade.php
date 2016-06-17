@extends('layouts.master')

@section('main')
    @include('partials.navigation')

    @include('partials.sidebar')

    <div class="content-wrapper">
        @include('partials.content_header')

        <section class="content">
            @yield('content')
        </section>
    </div>
@stop