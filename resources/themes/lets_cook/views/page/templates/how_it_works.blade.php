@extends('layouts.master')

@section('content')

    <main class="main how-works">
        <h1 class="how-works__title static-georgia-title">{!! $model->name !!}</h1>

        @widget__banner('how_it_works')

    </main>

@endsection