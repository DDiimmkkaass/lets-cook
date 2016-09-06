@extends('layouts.master')

@section('content')

    <main class="main recipes-list articles-list">
        <h1 class="articles-list__title static-georgia-title"><span>Список рецептов</span></h1>

        @if (count($list))
            @include('recipe.partials.search_form')

            @include('recipe.partials.filters')
        @endif

        @include('recipe.partials.list')

        @if (count($list))

            @include('recipe.partials.pagination')
        @endif
    </main>

@endsection