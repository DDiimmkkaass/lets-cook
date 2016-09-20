<div class="margin-bottom-15">
    <div class="form-group margin-bottom-25">
        <h3 class="col-xs-12 margin-bottom-5 font-size-16 text-left margin-top-0">
            @lang('labels.week')
        </h3>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('weekly_menu_id', $weekly_menus, old('weekly_menu_id') ?: $weekly_menu_id, ['id' => 'order_weekly_menu_select', 'class' => 'form-control select2 order-weekly-menu-select input-sm', 'aria-hidden' => 'true']) !!}
        </div>
    </div>
</div>

<div class="margin-bottom-15">
    <div class="form-group margin-bottom-25 @if ($errors->has('basket_id')) has-error @endif">
        <h3 class="col-xs-12 margin-bottom-5 font-size-16 text-left margin-top-0">
            @lang('labels.main_basket')
        </h3>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('basket_id', $baskets, $basket_id, ['id' => 'order_basket_select', 'class' => 'form-control select2 order-basket-select input-sm', 'aria-hidden' => 'true']) !!}

            <input id="old_basket_id" type="hidden" value="{!! $basket_id !!}">
            <input id="new_basket_id" type="hidden" value="{!! old('basket_id') ?: $basket_id !!}">
            <div class="clearfix"></div>

            {!! $errors->first('basket_id', '<p class="help-block error position-relative">:message</p>') !!}
        </div>
    </div>
</div>

<div class="margin-bottom-15">
    <div class="form-group margin-bottom-25 @if ($errors->has('recipes_count')) has-error @endif">
        <h3 class="col-xs-12 margin-bottom-5 font-size-16 text-left margin-top-0">
            @lang('labels.recipes_count')
        </h3>
        <div class="col-sm-12 full-width-select">
            <select class="form-control select2 order-recipes-count-select" name="recipes_count" id="recipes_count">
                <option value="">@lang('labels.please_select')</option>
                @for($i = config('order.min_recipes'); $i <= config('weekly_menu.menu_days'); $i++)
                    <option value="{!! $i !!}"
                            @if ((old('recipes_count') ?: $recipes_count) == $i) selected="selected" @endif>
                        {!! $i !!}
                    </option>
                    @endfor
            </select>
        </div>
    </div>
</div>

<div class="recipes-add-control">
    <div class="form-group margin-bottom-25 @if ($errors->has('recipes')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.add_recipes')
        </div>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('add_recipes', ['' => trans('labels.please_select_basket')], null, ['id' => 'order_recipe_select', 'class' => 'form-control select2 order-recipe-select input-sm', 'aria-hidden' => 'true']) !!}

            <div class="clearfix"></div>

            @if ($errors->has('recipes'))
                {!! $errors->first('recipes', '<p class="help-block error position-relative">:message</p>') !!}
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
                <tr id="recipe_{!! $recipe->basket_recipe_id !!}" class="basket-{!! $basket_id !!}-recipe">
                    <td>
                        <div class="form-group @if ($errors->has('recipes.old.' .$recipe->id. '.basket_recipe_id')) has-error @endif">
                            {!! Form::text('recipes[old][' .$recipe->id. '][recipe_id]', $recipe->recipe_id, ['id' => 'recipes.old.' .$recipe->id. '.recipe_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}

                            {!! Form::hidden('recipes[old][' .$recipe->id. '][basket_recipe_id]', $recipe->basket_recipe_id) !!}
                            {!! Form::hidden('recipes[old][' .$recipe->id. '][name]', $recipe->recipe->getName()) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            @if ($recipe->image)
                                @include('partials.image', ['src' => $recipe->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                            @endif
                            {!! link_to_route('admin.recipe.show', $recipe->recipe->getName(), $recipe->recipe_id, ['target' => '_blank']) !!}
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

                                {!! Form::hidden('recipes[new][' .$recipe_key. '][image]', $recipe['image']) !!}
                                {!! Form::hidden('recipes[new][' .$recipe_key. '][name]', $recipe['name']) !!}
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
