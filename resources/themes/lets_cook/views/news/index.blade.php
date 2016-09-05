@extends('layouts.master')

@section('content')

    <main class="main blog-list articles-list">
        <h1 class="articles-list__title static-georgia-title" data-page="articles"><span>Статьи</span></h1>

        @include('news.partials.filters')

        @include('news.partials.list')

        @include('news.partials.pagination')
    </main>

@endsection