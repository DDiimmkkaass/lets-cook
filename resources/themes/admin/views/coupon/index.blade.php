@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="coupons-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.code')],
                                ['text' => trans('labels.discount')],
                                ['text' => trans('labels.discount_type')],
                                ['text' => trans('labels.baskets_type')],
                                ['text' => trans('labels.coupon_parameters')],
                                ['text' => trans('labels.period_of_using')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 8]]
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop