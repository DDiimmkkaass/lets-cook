@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="coupons-using-table">
                        {!!
                            TablesBuilder::create(
                                ['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover filtered-datatable"],
                                ['bStateSave' => false, 'order' => [[ 8, 'desc' ]]]
                            )
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.tags')],
                                ['text' => trans('labels.code')],
                                ['text' => trans('labels.user')],
                                ['text' => trans('labels.order')],
                                ['text' => trans('labels.order_total')],
                                ['text' => trans('labels.discount')],
                                ['text' => trans('labels.date')],
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 2]],
                                ['text' => Form::select('datatable_filters[tags]', $tags, null, ['class' => 'form-control select2 input-sm datatable-filter', 'multiple' => 'multiple'])],
                                ['attr' => ['colspan' => 5]],
                                ['text' => Form::text('datatable_filters[date_from]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.from')]).' - '.Form::text('datatable_filters[date_to]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.to')])],
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop