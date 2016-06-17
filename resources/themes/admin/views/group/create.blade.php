@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.group.store', 'role' => 'form']) !!}

                @include('views.group.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop