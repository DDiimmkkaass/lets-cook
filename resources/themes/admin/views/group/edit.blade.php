@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            {!! Form::model($model, array('role' => 'form', 'method' => 'put', 'route' => array('admin.group.update', $model->id))) !!}

                @include('views.group.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop