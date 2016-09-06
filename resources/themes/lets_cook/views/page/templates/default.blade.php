@extends('layouts.master')

@section('content')

    <main class="main static">
        <h1 class="static__title static-georgia-title">{!! $model->name !!}</h1>

        <section class="static__content static-content">
            <div class="static-content__wrapper">
                {!! $model->getContent() !!}
            </div>
        </section>
    </main>

@endsection