<div class="col-lg-12 padding-top-20">
    <div class="box box-primary">
        <div class="box-body">
            <div class="recipes-select-table">
                {!!
                    TablesBuilder::create([
                    'id' => "recipes_datable_".$basket_id."_".$portions,
                    'class' => "filtered-datatable table table-bordered table-striped table-hover",
                    'data-basket_id' => $basket_id,
                    'data-portions' => $portions,
                    ], [
                    'bStateSave' => true,
                    'ajax' => route('admin.recipe.index_find').'?datatable_filters[basket]='.$basket_id.'&datatable_filters[portions]='.$portions.'&datatable_filters[status]=1',
                    ])
                    ->addHead([
                        ['text' => trans('labels.id')],
                        ['text' => trans('labels.name')],
                        ['text' => trans('labels.tags')],
                        ['text' => trans('labels.price')],
                        ['text' => trans('labels.last_order')],
                        ['text' => trans('labels.actions')]
                    ])
                    ->addFoot([
                        ['attr' => ['colspan' => 1]],
                        ['text' => Form::text('datatable_filters[name]', '', ['class' => 'form-control input-sm datatable-filter'])],
                        ['text' => Form::select('datatable_filters[tags]', $tags, null, ['class' => 'form-control select2 input-sm datatable-filter', 'multiple' => 'multiple'])],
                        ['attr' => ['colspan' => 1]],
                        ['attr' => ['colspan' => 1]],
                        ['attr' => ['colspan' => 1]],
                    ])
                     ->make()
                !!}
            </div>
        </div>
    </div>
</div>