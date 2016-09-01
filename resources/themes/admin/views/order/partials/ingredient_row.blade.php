<tr id="ingredient_{!! $basket_recipe->id.'_'.$model->id !!}">
    <td>
        <div class="form-group @if ($errors->has('ingredients.new.' .$basket_recipe->id.'_'.$model->id. '.ingredient_id')) has-error @endif">
            {!! Form::text('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][ingredient_id]', $model->id, ['id' => 'ingredients.new.' .$model->id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->image)
                @include('partials.image', ['src' => $model['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.ingredient.show', $model->name . ' ('.$basket_recipe->recipe->name.')', $model->id, ['target' => '_blank']) !!}

            {!! Form::hidden('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][image]', $model->image) !!}
            {!! Form::hidden('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][name]', $model->name) !!}
            {!! Form::hidden('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][unit]', $model->unit_name) !!}
            {!! Form::hidden('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][basket_recipe_id]', $basket_recipe->id) !!}
            {!! Form::hidden('ingredients[new][' .$basket_recipe->id.'_'.$model->id. '][recipe_name]', $basket_recipe->recipe->name) !!}
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm"
                   name="ingredients[new][{!! $basket_recipe->id.'_'.$model->id !!}][count]"
                   required="required"
                   value="{!! $model->count !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            {!! $model->unit_name !!}
        </div>
    </td>
    <td class="text-center coll-actions">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>