@extends('layouts.master')

@section('main')
    @include('partials.navigation', array( 'user' => $user ))

    @include('partials.sidebar', array( 'user' => $user ))

    <div class="content-wrapper">
        @include('partials.content_header')

        <section class="content">
            @yield('content')
        </section>
    </div>
@stop