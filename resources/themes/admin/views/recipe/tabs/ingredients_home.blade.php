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
            {!! Form::select('add_ingredients', $ingredients, null, ['id' => 'ingredient_select', 'class' => 'form-control select2 ingredient-select input-sm', 'aria-hidden' => 'true', 'data-type' => 'home']) !!}

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

        @if (count($model->home_ingredients))
            @foreach($model->home_ingredients as $ingredient)
                <tr id="ingredient_{!! $ingredient->ingredient_id !!}">
                    <td>
                        <div class="form-group @if ($errors->has('ingredients_home.old.' .$ingredient->id. '.ingredient_id')) has-error @endif">
                            {!! Form::text('ingredients_home[old][' .$ingredient->id. '][ingredient_id]', $ingredient->ingredient_id, ['id' => 'ingredients_home.old.' .$ingredient->id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                            {!! Form::hidden('ingredients_home[old][' .$ingredient->id. '][type]', $ingredient->type) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            @if ($ingredient->ingredient->image)
                                @include('partials.image', ['src' => $ingredient->ingredient->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                            @endif
                            {!! link_to_route('admin.ingredient.show', $ingredient->ingredient->name, [$model->id], ['target' => '_blank']) !!} ({!! $ingredient->ingredient->unit->name !!})
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('ingredients_home.old.' .$ingredient->id. '.count')) has-error @endif">
                            {!! Form::text('ingredients_home[old][' .$ingredient->id. '][count]', $ingredient->count ?: 1, ['id' => 'ingredients_home.old.' .$ingredient->id. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('ingredients_home.old.' .$ingredient->id. '.position')) has-error @endif">
                            {!! Form::text('ingredients_home[old][' .$ingredient->id. '][position]', $ingredient->position ?: 0, ['id' => 'ingredients_home.old.' .$ingredient->id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
                        </div>
                    </td>
                    <td class="text-center coll-actions">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $ingredient->id !!}"
                           data-name="ingredients_home[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('ingredients_home.new')))
            @foreach(old('ingredients_home.new') as $ingredient_key => $ingredient)
                @if ($ingredient_key !== 'replaseme')
                    <tr id="ingredient_{!! $ingredient_key !!}">
                        <td>
                            <div class="form-group">
                                <div class="form-group @if ($errors->has('ingredients_home.new.' .$ingredient_key. '.ingredient_id')) has-error @endif">
                                    {!! Form::text('ingredients_home[new][' .$ingredient_key. '][ingredient_id]', $ingredient['ingredient_id'], ['id' => 'ingredients_home.new.' .$ingredient_key. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                                    {!! Form::hidden('ingredients_home[new][' .$ingredient_key. '][type]', $ingredient['type']) !!}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                @if ($ingredient['image'])
                                    @include('partials.image', ['src' => $ingredient['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                                @endif
                                {!! link_to_route('admin.ingredient.show', $ingredient['name'], [$model->id], ['target' => '_blank']) !!} ({!! $ingredient['unit'] !!})

                                {!! Form::hidden('ingredients_home[new][' .$ingredient_key. '][image]', $ingredient['image']) !!}
                                {!! Form::hidden('ingredients_home[new][' .$ingredient_key. '][name]', $ingredient['name']) !!}
                                {!! Form::hidden('ingredients_home[new][' .$ingredient_key. '][unit]', $ingredient['unit']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('ingredients_home.new.' .$ingredient_key. '.count')) has-error @endif">
                                {!! Form::text('ingredients_home[new][' .$ingredient_key. '][count]', $ingredient['count'], ['id' => 'ingredients_home.new.' .$ingredient_key. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('ingredients_home.new.' .$ingredient_key. '.position')) has-error @endif">
                                {!! Form::text('ingredients_home[new][' .$ingredient_key. '][position]', $ingredient['position'], ['id' => 'ingredients_home.new.' .$ingredient_key. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
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