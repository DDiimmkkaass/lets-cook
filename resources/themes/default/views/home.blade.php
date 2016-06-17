@extends('layouts.master')

@section('content')
    <h1>{!! $model->getTitle() !!}</h1>

    <p>{!! $model->getContent() !!}</p>
@endsection