<h3 class="margin-bottom-15">
    <div class="form-group margin-bottom-25 @if ($errors->has('basket')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.main_basket')
        </div>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('basket_id', $baskets, $basket ? $basket->id : null, ['id' => 'order_basket_select', 'class' => 'form-control select2 order-basket-select input-sm', 'aria-hidden' => 'true']) !!}

            <div class="clearfix"></div>

            {!! $errors->first('basket', '<p class="help-block error position-relative">:message</p>') !!}
        </div>
    </div>
</h3>

<div class="recipes-add-control">
    <div class="form-group margin-bottom-25 @if ($errors->has('recipes.new') || $errors->has('recipes.old')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.add_recipes')
        </div>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('add_recipes', ['' => trans('labels.please_select_basket')], null, ['id' => 'order_recipe_select', 'class' => 'form-control select2 order-recipe-select input-sm', 'aria-hidden' => 'true']) !!}

            <div class="clearfix"></div>

            @if ($errors->has('recipes.new') || $errors->has('recipes.old'))
                {!! $errors->first('recipes.new', '<p class="help-block error position-relative">:message</p>') !!}
                {!! $errors->first('recipes.old', '<p class="help-block error position-relative">:message</p>') !!}
            @endif
        </div>
    </div>
</div>

<div class="box-body table-responsive no-padding">
    <table class="table table-hover table-bordered duplication-items-table order-recipes-table">
        <tbody>
        <tr>
            <th class="col-sm-1 text-center">{!! trans('labels.id') !!}</th>
            <th>{!! trans('labels.name') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($recipes))
            @foreach($recipes as $recipe)
                <tr id="recipe_{!! $recipe->basket_recipe_id !!}">
                    <td>
                        <div class="form-group @if ($errors->has('recipes.old.' .$recipe->id. '.basket_recipe_id')) has-error @endif">
                            {!! Form::text('recipes[old][' .$recipe->id. '][recipe_id]', $recipe->recipe_id, ['id' => 'recipes.old.' .$recipe->id. '.recipe_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}

                            {!! Form::hidden('recipes[old][' .$recipe->id. '][basket_recipe_id]', $recipe->basket_recipe_id) !!}
                            {!! Form::hidden('recipes[old][' .$recipe->id. '][name]', $recipe->name) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            @if ($recipe->image)
                                @include('partials.image', ['src' => $recipe->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                            @endif
                            {!! link_to_route('admin.recipe.show', $recipe->name, $recipe->recipe_id, ['target' => '_blank']) !!}
                            <span class="lover-case">(@lang('labels.portions'): {!! $recipe->portions !!})</span>
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
                            <div class="form-group @if ($errors->has('recipes.new.' .$recipe_key. '.basket_recipe_id')) has-error @endif">
                                {!! Form::text('recipes[new][' .$recipe_key. '][recipe_id]', $recipe['recipe_id'], ['id' => 'recipes.new.' .$recipe_key. '.recipe_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                            </div>

                            {!! Form::hidden('recipes[new][' .$recipe_key. '][basket_recipe_id]', $recipe['basket_recipe_id']) !!}
                        </td>
                        <td>
                            <div class="form-group">
                                @if ($recipe['image'])
                                    @include('partials.image', ['src' => $recipe['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                                @endif
                                {!! link_to_route('admin.recipe.show', $recipe['name'], $recipe_key, ['target' => '_blank']) !!}
                                <span class="lover-case">(@lang('labels.portions')
                                    : {!! $recipe['recipe_portions'] !!})</span>

                                {!! Form::hidden('recipes[new][' .$recipe_key. '][image]', $recipe['image']) !!}
                                {!! Form::hidden('recipes[new][' .$recipe_key. '][name]', $recipe['name']) !!}
                                {!! Form::hidden('recipes[new][' .$recipe_key. '][recipe_portions]', $recipe['recipe_portions']) !!}
                            </div>
                        </td>
                        <td class="text-center coll-actions">
                            <a class="btn btn-flat btn-danger btn-xs action destroy"><i
                                        class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        </tbody>
    </table>
</div>
