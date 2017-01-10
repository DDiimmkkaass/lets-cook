@extends('layouts.simple')

@section('content')

    <section class="main">

        <div class="h-main-title">404</div>

        <h1 class="let-cook__subTitle">Страница не найдена</h1>

        <h2 class="let-cook__subTitle">Но мы, не оставим Вас голодными =)</h2>

        <a class="black-long-button" title="На главную" href="{!! localize_route('home') !!}">На главную</a>

    </section>

@endsection