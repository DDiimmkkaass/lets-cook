<div class="recipes-add-control">
    <div class="form-group margin-bottom-25">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.recipes')
        </div>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('add_recipes', $recipes, null, ['id' => 'recipe_select', 'class' => 'form-control select2 recipe-select input-sm', 'aria-hidden' => 'true']) !!}
        </div>
    </div>
</div>

<div class="box-body table-responsive no-padding">
    <table class="table table-hover table-bordered basket-recipes-table">
        <tbody>
        <tr>
            <th class="col-sm-1 text-center">{!! trans('labels.id') !!}</th>
            <th>{!! trans('labels.name') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.position') !!} <span class="required">*</span></th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->recipes))
            @foreach($model->recipes as $recipe)
                <tr id="recipe_{!! $recipe->recipe_id !!}">
                    <td>
                        <div class="form-group @if ($errors->has('recipes.old.' .$recipe->id. '.recipe_id')) has-error @endif">
                            {!! Form::text('recipes[old][' .$recipe->id. '][recipe_id]', $recipe->recipe_id, ['id' => 'recipes.old.' .$recipe->id. '.recipe_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            @if ($recipe->recipe->image)
                                @include('partials.image', ['src' => $recipe->recipe->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                            @endif
                            {!! $recipe->recipe->name !!} <span class="lover-case">(@lang('labels.portions'): {!! $recipe->recipe->portions !!})</span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('recipes.old.' .$recipe->id. '.position')) has-error @endif">
                            {!! Form::text('recipes[old][' .$recipe->id. '][position]', $recipe->position ?: 0, ['id' => 'recipes.old.' .$recipe->id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
                        </div>
                    </td>
                    <td class="text-center coll-actions">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $recipe->id !!}"
                           data-name="recipes[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('recipes.new')))
            @foreach(old('recipes.new') as $recipe_key => $recipe)
                @if ($recipe_key !== 'replaseme')
                    <tr id="recipe_{!! $recipe_key !!}">
                        <td>
                            <div class="form-group">
                                <div class="form-group @if ($errors->has('recipes.new.' .$recipe_key. '.recipe_id')) has-error @endif">
                                    {!! Form::text('recipes[new][' .$recipe_key. '][recipe_id]', $recipe['recipe_id'], ['id' => 'recipes.new.' .$recipe_key. '.recipe_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                @if ($recipe['image'])
                                    @include('partials.image', ['src' => $recipe['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                                @endif
                                {!! $recipe['name'] !!} <span class="lover-case">(@lang('labels.portions'): {!! $recipe['recipe_portions'] !!})</span>

                                {!! Form::hidden('recipes[new][' .$recipe_key. '][image]', $recipe['image']) !!}
                                {!! Form::hidden('recipes[new][' .$recipe_key. '][name]', $recipe['name']) !!}
                                {!! Form::hidden('recipes[new][' .$recipe_key. '][recipe_portions]', $recipe['recipe_portions']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('recipes.new.' .$recipe_key. '.position')) has-error @endif">
                                {!! Form::text('recipes[new][' .$recipe_key. '][position]', $recipe['position'], ['id' => 'recipes.new.' .$recipe_key. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>
                        </td>
                        <td class="text-center coll-actions">
                            <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        </tbody>
    </table>
</div>