@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="recipes-table">
                        {!!
                            TablesBuilder::create(['id' => "recipes_datable", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.basket')],
                                ['text' => trans('labels.portions')],
                                ['text' => trans('labels.main_ingredient')],
                                ['text' => trans('labels.status')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 1]],
                                ['text' => Form::text('recipe_filters[name]', '', ['class' => 'form-control input-sm recipe-filter'])],
                                ['text' => Form::select('recipe_filters[basket]', $baskets, null, ['class' => 'form-control select2 input-sm recipe-filter'])],
                                ['text' => Form::text('recipe_filters[portions]', '', ['class' => 'form-control input-sm recipe-filter'])],
                                ['text' => Form::text('recipe_filters[main_ingredient]', '', ['class' => 'form-control input-sm recipe-filter'])],
                                ['text' => Form::select('recipe_filters[status]', $statuses, null, ['class' => 'form-control select2 input-sm recipe-filter'])],
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