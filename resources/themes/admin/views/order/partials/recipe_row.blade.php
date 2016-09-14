<tr id="recipe_{!! $model->id !!}">
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="recipes[new][{!! $model->id !!}][recipe_id]"
                   readonly="readonly" value="{!! $model->recipe_id !!}">

            <input type="hidden" name="recipes[new][{!! $model->id !!}][basket_recipe_id]" value="{!! $model->id !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->recipe->image)
                @include('partials.image', ['src' => $model->recipe->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.recipe.show', $model->getName(), $model->recipe->id, ['target' => '_blank']) !!}

            <input type="hidden" name="recipes[new][{!! $model->id !!}][image]" value="{!! $model->recipe->image !!}">
            <input type="hidden" name="recipes[new][{!! $model->id !!}][name]" value="{!! $model->getName() !!}">
        </div>
    </td>
    <td class="text-center">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>