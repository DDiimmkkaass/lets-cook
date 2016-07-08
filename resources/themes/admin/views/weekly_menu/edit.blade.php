@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'put', 'class' => 'form-horizontal', 'route' => ['admin.weekly_menu.update', $model->id]]) !!}

            @include('weekly_menu.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop