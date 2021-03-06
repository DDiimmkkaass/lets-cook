<div class="ingredients-add-control">
    <div class="form-group margin-bottom-25">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.add_ingredients')
        </div>

        <div class="col-sm-12 full-width-select">
            <select name="add_ingredients"
                    id="order_ingredient_select"
                    class="form-control select2 order-ingredient-select input-sm"
                    aria-hidden="true">
                <option value="">@lang('labels.please_select_basket')</option>
            </select>
        </div>
    </div>
</div>

<div class="box-body table-responsive no-padding">
    <table class="table table-hover table-bordered duplication-items-table order-ingredients-table">
        <tbody>
        <tr>
            <th class="col-sm-1 text-center">{!! trans('labels.id') !!}</th>
            <th>{!! trans('labels.name') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.count') !!} <span class="required">*</span></th>
            <th class="col-sm-2">{!! trans('labels.units') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if ($model->ingredients->count())
            @foreach($model->ingredients as $ingredient)
                <tr id="ingredient_{!! $ingredient->basket_recipe_id.'_'.$ingredient->ingredient_id !!}"
                    class="basket-{!! $basket_id !!}-ingredient">
                    <td>
                        <div class="form-group @if ($errors->has('ingredients.old.' .$ingredient->id. '.ingredient_id')) has-error @endif">
                            {!! Form::text('ingredients[old][' .$ingredient->id. '][ingredient_id]', $ingredient->ingredient_id, ['id' => 'ingredients.old.' .$ingredient->id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}

                            {!! Form::hidden('ingredients[old][' .$ingredient->id. '][name]', $ingredient->getName()) !!}
                            {!! Form::hidden('ingredients[old][' .$ingredient->id. '][unit]', $ingredient->ingredient->sale_unit->name) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            @if ($ingredient->getImage())
                                @include('partials.image', ['src' => $ingredient->getImage(), 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                            @endif
                            {!! link_to_route('admin.ingredient.show', $ingredient->getName().' ('.$ingredient->recipe->recipe->name.')', $ingredient->ingredient_id, ['target' => '_blank']) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('ingredients.old.' .$ingredient->id. '.count')) has-error @endif">
                            {!! Form::text('ingredients[old][' .$ingredient->id. '][count]', $ingredient->count ?: 1, ['id' => 'ingredients.old.' .$ingredient->id. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {!! $ingredient->ingredient->sale_unit->name !!}
                        </div>
                    </td>
                    <td class="text-center coll-actions">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $ingredient->id !!}"
                           data-name="ingredients[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('ingredients.new')))
            @foreach(old('ingredients.new') as $ingredient_key => $ingredient)
                @if ($ingredient_key !== 'replaseme')
                    <tr id="ingredient_{!! $ingredient_key !!}">
                        <td>
                            <div class="form-group @if ($errors->has('ingredients.new.' .$ingredient_key. '.ingredient_id')) has-error @endif">
                                {!! Form::text('ingredients[new][' .$ingredient_key. '][ingredient_id]', $ingredient['ingredient_id'], ['id' => 'ingredients.new.' .$ingredient_key. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                @if ($ingredient['image'])
                                    @include('partials.image', ['src' => $ingredient['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
                                @endif
                                {!! link_to_route('admin.ingredient.show', $ingredient['name'].' ('.$ingredient['recipe_name'].')', $ingredient['ingredient_id'], ['target' => '_blank']) !!}

                                {!! Form::hidden('ingredients[new][' .$ingredient_key. '][image]', $ingredient['image']) !!}
                                {!! Form::hidden('ingredients[new][' .$ingredient_key. '][name]', $ingredient['name']) !!}
                                {!! Form::hidden('ingredients[new][' .$ingredient_key. '][unit]', $ingredient['unit']) !!}
                                {!! Form::hidden('ingredients[new][' .$ingredient_key. '][basket_recipe_id]', $ingredient['basket_recipe_id']) !!}
                                {!! Form::hidden('ingredients[new][' .$ingredient_key. '][recipe_name]', $ingredient['recipe_name']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('ingredients.new.' .$ingredient_key. '.count')) has-error @endif">
                                {!! Form::text('ingredients[new][' .$ingredient_key. '][count]', $ingredient['count'], ['id' => 'ingredients.new.' .$ingredient_key. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {!! $ingredient['unit'] !!}
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