@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'put', 'class' => 'form-horizontal', 'route' => ['admin.text_widget.update', $model->id]]) !!}

            @include('text_widget.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop