@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.tag_category.store', 'role' => 'form', 'class' => 'form-horizontal']) !!}

            @include('tag_category.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop