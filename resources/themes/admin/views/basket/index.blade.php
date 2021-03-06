@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="baskets-table">
                        @if ($type == 'basic')
                            {!!
                                TablesBuilder::create(
                                    ['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"],
                                    ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                                )
                                ->addHead([
                                    ['text' => trans('labels.id')],
                                    ['text' => trans('labels.name')],
                                    ['text' => trans('labels.position')],
                                    ['text' => trans('labels.actions')]
                                ])
                                ->addFoot([
                                    ['attr' => ['colspan' => 4]]
                                ])
                                 ->make()
                            !!}
                        @elseif ($type == 'additional')
                            {!!
                                TablesBuilder::create(
                                    ['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"],
                                    ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                                )
                                ->addHead([
                                    ['text' => trans('labels.id')],
                                    ['text' => trans('labels.name')],
                                    ['text' => trans('labels.position')],
                                    ['text' => trans('labels.price')],
                                    ['text' => trans('labels.places')],
                                    ['text' => trans('labels.actions')]
                                ])
                                ->addFoot([
                                    ['attr' => ['colspan' => 6]]
                                ])
                                 ->make()
                            !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop