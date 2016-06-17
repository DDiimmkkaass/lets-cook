@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="variables-table margin-top-10">

                @foreach($list as $item)

                    <div class="box box-primary">
                        {!! Form::model($item, ['role' => 'form', 'method' => 'post', 'route' => ['admin.variable.value.update'], 'class' => 'variable-value-form form-horizontal']) !!}

                        <input type="hidden" name="variable_id" value="{!! $item->id !!}">

                        <div class="box-body">
                            <input type="hidden" name="type" value="{!! $item->type !!}">
                            <input type="hidden" name="multilingual" value="{!! $item->multilingual !!}">

                            <label class="control-label col-xs-4 col-sm-3 col-md-2 text-right variable-value-label">
                                {!! $item->name !!}
                            </label>

                            <div class="col-xs-12 col-sm-9 col-md-10">
                                @include('variable.types.'.$item->getStringType())

                                <div class="row form-group">
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 ">
                                        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['id' => 'status', 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="row form-group">
                                    @if ($user->hasAccess('variablevalue.write'))
                                        <div class="col-md-4 pull-right ta-right">
                                            {!! Form::submit(trans('labels.save'), ['class' => 'btn btn-success btn-flat save-variable-value']) !!}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {!! Form::close() !!}
                    </div>

                @endforeach

            </div>
        </div>
    </div>

@stop