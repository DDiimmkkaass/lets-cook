@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(array('route' => ['admin.user.new_password.post', $model->id], 'class' => 'form-horizontal', 'method'=>'post')) !!}

                @include('user.partials._buttons', ['without_password_change' => true, 'class' => 'buttons-top', 'back_url' => route('admin.user.edit', $model->id)])

                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group required @if ($errors->has('password')) has-error @endif">
                                {!! Form::label('password', trans('labels.password'), ['class' => "col-xs-2 control-label"]) !!}

                                <div class="col-xs-3">
                                    {!! Form::text('password', null, ['placeholder' => trans('labels.password'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                    {!! $errors->first('password', '<p class="help-block error">:message</p>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group required @if ($errors->has('password_confirmation')) has-error @endif">
                                {!! Form::label('password_confirmation', trans('labels.password_confirmation'), ['class' => "col-xs-2 control-label"]) !!}

                                <div class="col-xs-3">
                                    {!! Form::text('password_confirmation', null, ['placeholder' => trans('labels.password_confirmation'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                    {!! $errors->first('password_confirmation', '<p class="help-block error">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('user.partials._buttons', ['without_password_change' => true, 'back_url' => route('admin.user.edit', $model->id)])

            {!! Form::close() !!}
        </div>
    </div>

@stop