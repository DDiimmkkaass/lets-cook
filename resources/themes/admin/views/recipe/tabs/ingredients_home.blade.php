<div class="ingredients-add-control">
    <div class="form-group margin-bottom-10">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.categories')
        </div>
        <div class="col-sm-12 full-width-select">
            {!! Form::select('add_category', $ingredient_categories, null, ['id' => 'ingredient_category_select', 'class' => 'form-control select2 ingredient-category-select input-sm', 'aria-hidden' => 'true']) !!}
        </div>
    </div>

    <div class="form-group margin-bottom-25 @if ($errors->has('ingredients_home')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.ingredients')
        </div>
        <div class="col-sm-12 full-width-select">
            <div class="input-group">
                {!! Form::select('add_ingredients', $ingredients, null, ['id' => 'ingredient_select', 'class' => 'form-control select2 ingredient-select input-sm', 'aria-hidden' => 'true', 'data-type' => 'home']) !!}

                @if ($user->hasAccess('ingredient.create'))
                    <div title="@lang('labels.add_ingredient')" data-href="{!! route('admin.ingredient.quick_create') !!}" class="input-group-addon get-ingredient-quick-create">
                        <i class="fa fa-plus"></i>
                    </div>
                @endif
            </div>

            <div class="position-relative error-block">
                {!! $errors->first('ingredients_home', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="box-body table-responsive no-padding">
    <table class="table table-hover table-bordered recipe-ingredients-table">
        <tbody>
        <tr>
            <th class="col-sm-1 text-center">{!! trans('labels.id') !!}</th>
            <th>{!! trans('labels.name') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.count') !!} <span class="required">*</span></th>
            <th class="col-sm-1 text-center">{!! trans('labels.position') !!} <span class="required">*</span></th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->home_ingredients) && !isset($copy))
            @foreach($model->home_ingredients as $ingredient)

                @include('recipe.partials.ingredient_old_row', ['key' => 'ingredients_home'])

            @endforeach
        @endif

        @if (count(old('ingredients_home.new')))
            @foreach(old('ingredients_home.new') as $ingredient_key => $ingredient)

                @include('recipe.partials.ingredient_new_row', ['type' => 'home', 'key' => 'ingredients_home', 'id' => $ingredient_key])

            @endforeach
        @endif

        @if (isset($copy) && !count(old('ingredients_home.new')))
            @foreach($model->home_ingredients as $ingredient)

                @include('recipe.partials.copy_ingredient_new_row', ['type' => 'home', 'key' => 'ingredients_home', 'id' => $ingredient->ingredient_id])

            @endforeach
        @endif

        </tbody>
    </table>
</div>