@extends('layouts.listable')

@section('content')

    @if (empty($history))
        @include('order.partials.summary_table')
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="orders-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "filtered-datatable table table-bordered table-striped table-hover"], ['bStateSave' => true, 'order' => [[ 5, 'asc' ]]])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.user')],
                                ['text' => trans('labels.phones')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.basket_name').'/'.trans('labels.price')],
                                ['text' => trans('labels.total_cost')],
                                ['attr' => ['class' => 'col-sm-1'], 'text' => trans('labels.status')],
                                ['text' => trans('labels.coupon_simple')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.delivery_date')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.address').'/'.trans('labels.comments')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 7]],
                                ['text' => Form::text('datatable_filters[delivery_date_from]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.from')]).' - '.Form::text('datatable_filters[delivery_date_to]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.to')])],
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