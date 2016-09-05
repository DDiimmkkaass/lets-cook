@extends('layouts.master')

@section('content')

    <main class="main blog-simple article-simple">
        <h1 class="article-simple__title static-georgia-title" data-page="articles"><span>{!! $model->name !!}</span></h1>

        <div class="article-simple__img" style="background-image: url({!! thumb($model->image) !!});"></div>

        <div class="article-simple__content">
            @include('partials.modules.social_share')

            @if ($model->tags->count())
            <ul class="article-simple__tags article-tags">
                @foreach($model->tags as $tag)
                    <li class="article-tags__item">{!! $tag->tag->name !!}</li>
                @endforeach
            </ul>
            @endif

            <div class="article-simple__text">
                {!! $model->getContent() !!}
            </div>
        </div>
    </main>

@endsection