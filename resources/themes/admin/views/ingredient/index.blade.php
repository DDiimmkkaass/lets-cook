@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="ingredients-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "filtered-datatable table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.category')],
                                ['text' => trans('labels.unit')],
                                ['text' => trans('labels.supplier')],
                                ['text' => trans('labels.price')],
                                ['text' => trans('labels.sale_price')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 6]],
                                ['text' => Form::select('datatable_filters[sale_price]', ['0' => trans('labels.all'), '-1' => trans('labels.not_sales'), '1' => trans('labels.in_sales')], null, ['class' => 'form-control select2 input-sm datatable-filter'])],
                                ['attr' => ['colspan' => 1]],
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop