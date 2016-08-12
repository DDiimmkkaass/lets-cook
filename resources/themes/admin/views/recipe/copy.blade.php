@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['route' => 'admin.recipe.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            {!! Form::hidden('parent_id', $model->id) !!}
            {!! Form::hidden('bind', $bind) !!}

            @include('recipe.partials._form', ['copy' => true])

            {!! Form::close() !!}
        </div>
    </div>
@stop