@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="groups-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                                ->addHead([
                                    ['text' => trans('labels.id')],
                                    ['text' => trans('labels.fio')],
                                    ['text' => trans('labels.email')],
                                    ['text' => trans('labels.phone')],
                                    ['text' => trans('labels.activated')],
                                    ['text' => trans('labels.actions')]
                                ])
                                ->addFoot([
                                    ['attr' => ['colspan' => 6]]
                                ])
                                ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
