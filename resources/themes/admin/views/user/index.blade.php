@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="groups-table">
                        {!!
                            TablesBuilder::create(
                                    ['id' => "datatable1", 'class' => "filtered-datatable table table-bordered table-striped table-hover"],
                                    ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                                )
                                ->addHead([
                                    ['text' => trans('labels.id')],
                                    ['text' => trans('labels.fio')],
                                    ['text' => trans('labels.email')],
                                    ['text' => trans('labels.phone')],
                                    ['text' => trans('labels.additional_phone')],
                                    ['text' => trans('labels.city')],
                                    ['text' => trans('labels.activated')],
                                    ['text' => trans('labels.actions')]
                                ])
                                ->addFoot([
                                    ['attr' => ['colspan' => 5]],
                                    ['text' => Form::text('datatable_filters[city_name]', '', ['class' => 'form-control input-sm datatable-filter'])],
                                    ['attr' => ['colspan' => 2]]
                                ])
                                ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
