@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.article.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('views.article.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop