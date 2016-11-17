@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{!! route('admin.subscribe.export') !!}" class="btn btn-sm btn-flat btn-success pull-right">
                        @lang('labels.export_in_csv')
                    </a>
                </div>

                <div class="box-body">
                    <div class="subscribes-table">
                        {!!
                            TablesBuilder::create(
                                ['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"],
                                ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                            )
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.email')],
                                ['text' => trans('labels.date')],
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