@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => ['admin.basket.store', $type], 'role' => 'form', 'class' => 'form-horizontal']) !!}

            {!! Form::hidden('type', $type) !!}

            @include('basket.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop