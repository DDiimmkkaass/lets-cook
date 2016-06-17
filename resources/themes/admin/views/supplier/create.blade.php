@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.supplier.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('supplier.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop