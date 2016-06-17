@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(array('enctype'=>'multipart/form-data', 'route' => 'admin.user.store', 'class' => 'form-horizontal')) !!}

                @include('user.partials._form', ['without_password_change' => true])

            {!! Form::close() !!}
        </div>
    </div>

@stop
