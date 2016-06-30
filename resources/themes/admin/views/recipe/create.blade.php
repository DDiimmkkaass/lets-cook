@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.recipe.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('recipe.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop