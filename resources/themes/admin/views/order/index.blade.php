@extends('layouts.listable')

@section('content')

    @include('order.partials.summary_table')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="orders-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "filtered-datatable table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.user')],
                                ['text' => trans('labels.type')],
                                ['text' => trans('labels.payment_method')],
                                ['text' => trans('labels.status')],
                                ['text' => trans('labels.created_at')],
                                ['text' => trans('labels.delivery_date')],
                                ['text' => trans('labels.order_total')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 6]],
                                ['text' => Form::text('datatable_filters[delivery_date_from]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.from')]).' - '.Form::text('datatable_filters[delivery_date_to]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.to')])],
                                ['attr' => ['colspan' => 2]],
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop