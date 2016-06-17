@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'post', 'class' => 'form-horizontal', 'route' => ['admin.variable.store']]) !!}

            @include('variable.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop