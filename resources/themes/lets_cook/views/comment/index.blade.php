@extends('layouts.master')

@section('content')

<main class="main comments-list articles-list">
    <h1 class="articles-list__title static-georgia-title" data-page="comments"><span>Отзывы</span></h1>

    <div class="articles-list-filter__panel"></div>
    
    @include('comment.partials.list')

    @if (count($list))
        @include('comment.partials.pagination')
    @endif
</main>

@endsection

