@extends('layouts.master')

@section('content')

    <main class="main blog-list articles-list">
        <h1 class="articles-list__title static-georgia-title" data-page="articles"><span>Статьи</span></h1>

        @include('article.partials.filters')

        @include('article.partials.list')

        @include('article.partials.pagination')
    </main>

@endsection