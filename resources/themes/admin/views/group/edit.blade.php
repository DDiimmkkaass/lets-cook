@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'put', 'route' => ['admin.group.update', $model->id]]) !!}

                @include('views.group.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop