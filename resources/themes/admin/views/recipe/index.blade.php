@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="recipes-table">
                        {!!
                            TablesBuilder::create(
                                ['id' => "recipes_datable", 'class' => "filtered-datatable table table-bordered table-striped table-hover"],
                                ['bStateSave' => true, 'order' => [[ 0, 'desc' ]]]
                            )
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.basket')],
                                ['text' => trans('labels.portions')],
                                ['text' => trans('labels.tags')],
                                ['text' => trans('labels.price')],
                                ['text' => trans('labels.last_uses')],
                                ['text' => trans('labels.status')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 1]],
                                ['text' => Form::text('datatable_filters[name]', '', ['class' => 'form-control input-sm datatable-filter'])],
                                ['text' => Form::select('datatable_filters[basket]', $baskets, null, ['class' => 'form-control select2 input-sm datatable-filter'])],
                                ['text' => Form::select('datatable_filters[portions]', $portions, null, ['class' => 'form-control select2 input-sm datatable-filter'])],
                                ['text' => Form::select('datatable_filters[tags]', $tags, null, ['class' => 'form-control select2 input-sm datatable-filter', 'multiple' => 'multiple'])],
                                ['text' => Form::text('datatable_filters[price_from]', '', ['class' => 'form-control input-sm datatable-filter', 'placeholder' => trans('labels.from')]).' - '.Form::text('datatable_filters[price_to]', '', ['class' => 'form-control input-sm datatable-filter', 'placeholder' => trans('labels.to')])],
                                ['text' => Form::text('datatable_filters[last_uses_from]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.from')]).' - '.Form::text('datatable_filters[last_uses_to]', '', ['class' => 'form-control input-sm datatable-filter datatable-date-filter inputmask-birthday datepicker-birthday', 'placeholder' => trans('labels.to')])],
                                ['text' => Form::select('datatable_filters[status]', $statuses, null, ['class' => 'form-control select2 input-sm datatable-filter'])],
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