@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.text_widget.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('text_widget.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop