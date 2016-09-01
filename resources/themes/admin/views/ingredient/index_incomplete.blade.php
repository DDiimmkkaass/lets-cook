@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 margin-bottom-10 text-right">
                            <span class="line-height-28 margin-right-10">@lang('labels.filter_as_action'):</span>
                            <div class="col-md-4 col-lg-2 pull-right no-padding full-width-select text-center">
                                {!! Form::select('filter', $filter_types, request('filter', null), ['class' => 'incomplete-ingredients-filter select2 input-sm col-xs-5 form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="ingredients-table incomplete-ingredients-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.category')],
                                ['text' => trans('labels.unit')],
                                ['text' => trans('labels.sale_units')],
                                ['text' => trans('labels.supplier')],
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 5]]
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop