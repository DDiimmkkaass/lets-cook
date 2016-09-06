@extends('layouts.master')

@section('content')

    <main class="main blog-list articles-list">
        <h1 class="articles-list__title static-georgia-title" data-page="articles"><span>Статьи</span></h1>

        @if (count($list))
            @include('news.partials.filters')
        @endif

        @include('news.partials.list')

        @if (count($list))
            @include('news.partials.pagination')
        @endif
    </main>

@endsection