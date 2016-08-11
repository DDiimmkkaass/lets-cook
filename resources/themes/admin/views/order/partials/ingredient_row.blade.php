<tr id="ingredient_{!! $model->id !!}">
    <td>
        <div class="form-group @if ($errors->has('ingredients.new.' .$model->id. '.ingredient_id')) has-error @endif">
            {!! Form::text('ingredients[new][' .$model->id. '][ingredient_id]', $model->id, ['id' => 'ingredients.new.' .$model->id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->image)
                @include('partials.image', ['src' => $model['image'], 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.ingredient.show', $model->name, $model->id, ['target' => '_blank']) !!}

            {!! Form::hidden('ingredients[new][' .$model->id. '][image]', $model->image) !!}
            {!! Form::hidden('ingredients[new][' .$model->id. '][name]', $model->name) !!}
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="ingredients[new][{!! $model->id !!}][count]"
                   required="required"
                   value="1">
        </div>
    </td>
    <td class="text-center coll-actions">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>