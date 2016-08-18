@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'post', 'class' => 'form-horizontal', 'route' => ['admin.order.store']]) !!}

            @include('order.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop