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
                            TablesBuilder::create(
                                ['id' => "datatable1", 'class' => "filtered-datatable table table-bordered table-striped table-hover"],
                                ['bStateSave' => true, 'order' => empty($history) ? [[ 5, 'asc' ]] : [[ 0, 'desc' ]]]
                            )
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.user')],
                                ['text' => trans('labels.phones')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.basket_name').'/'.trans('labels.price')],
                                ['text' => trans('labels.total_cost')],
                                ['attr' => ['class' => 'col-sm-1'], 'text' => trans('labels.status')],
                                ['text' => trans('labels.coupon_simple')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.delivery_date')],
                                ['attr' => ['class' => 'col-sm-2'], 'text' => trans('labels.address').'/'.trans('labels.order_comments')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 7]],
                                ['text' => '<div class="btn btn-flat btn-default no-margin btn-sm week-pagination prev-week"><i class="fa fa-angle-double-left" aria-hidden="true"></i></div> '.
                                    Form::text('datatable_filters[week]', active_week()->weekOfYear, ['class' => 'form-control input-sm datatable-filter datatable-date-filter datatable-week-filter inputmask-week', 'placeholder' => trans('labels.week'), 'data-current' => active_week()->weekOfYear]).' '.
                                    Form::text('datatable_filters[year]', active_week()->year, ['class' => 'form-control input-sm datatable-filter datatable-date-filter datatable-year-filter inputmask-year', 'placeholder' => trans('labels.year'), 'data-current' => active_week()->year]).' '.
                                    '<div class="btn btn-flat btn-default no-margin btn-sm week-pagination next-week"><i class="fa fa-angle-double-right" aria-hidden="true"></i></div> '.
                                    '<div class="btn btn-flat btn-default no-margin btn-sm current-week pull-right" title="'.trans('labels.current_week').'"><i class="fa fa-clock-o" aria-hidden="true"></i></div>'
                                ],
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