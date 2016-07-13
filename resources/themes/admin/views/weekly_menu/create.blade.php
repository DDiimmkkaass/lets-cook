@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['route' => 'admin.weekly_menu.store', 'role' => 'form', 'class' => 'form-horizontal weekly-menu-form']) !!}

            @include('weekly_menu.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop