@extends('layouts.master')

@section('content')

    <main class="main recipes-list articles-list">
        <h1 class="articles-list__title static-georgia-title"><span>Список рецептов</span></h1>

        @include('recipe.partials.search_form')

        @include('recipe.partials.filters')

        @include('recipe.partials.list')

        @include('recipe.partials.pagination')
    </main>

@endsection