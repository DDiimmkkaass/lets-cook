@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="weekly-menus-table">
                        {!!
                            TablesBuilder::create(
                                ['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"],
                                ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                            )
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.week')],
                                ['text' => trans('labels.year')],
                                ['text' => trans('labels.weekly_menu_days_labels')],
                                ['text' => trans('labels.actions')],
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 5]]
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop