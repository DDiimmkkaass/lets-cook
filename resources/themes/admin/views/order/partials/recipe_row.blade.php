<tr id="recipe_{!! $model->id !!}">
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="recipes[new][{!! $model->id !!}][basket_recipe_id]"
                   readonly="readonly" value="{!! $model->id !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->recipe->image)
                @include('partials.image', ['src' => $model->recipe->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.recipe.show', $model->recipe->name, $model->recipe->id, ['target' => '_blank']) !!}
            <span class="lover-case">(@lang('labels.portions'): {!! $model->recipe->portions !!})</span>

            <input type="hidden" name="recipes[new][{!! $model->id !!}][image]" value="{!! $model->recipe->image !!}">
            <input type="hidden" name="recipes[new][{!! $model->id !!}][name]" value="{!! $model->recipe->name !!}">
            <input type="hidden" name="recipes[new][{!! $model->id !!}][recipe_portions]" value="{!! $model->recipe->portions !!}">
        </div>
    </td>
    <td class="text-center">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>