@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'put', 'class' => 'form-horizontal', 'route' => ['admin.parameter.update', $model->id]]) !!}

            @include('parameter.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop