@extends('layouts.master')

@section('content')

    <main class="main articles-list">
        <h1 class="articles-list__title static-georgia-title" data-page="articles"><span>Статьи</span></h1>

        @if (count($list))
            @include('article.partials.filters')
        @endif

        @include('article.partials.list')

        @if (count($list))
            @include('article.partials.pagination')
        @endif
    </main>

@endsection