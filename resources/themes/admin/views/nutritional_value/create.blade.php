@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.nutritional_value.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('nutritional_value.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop