@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="purchase-table">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.week'), 'attr' => ['class' => 'col-sm-7']],
                                ['text' => trans('labels.year'), 'attr' => ['class' => 'col-sm-4']],
                                ['text' => trans('labels.actions'), 'attr' => ['class' => 'col-sm-1']],
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 3]]
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop