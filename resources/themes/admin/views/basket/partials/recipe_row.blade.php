<tr id="recipe_{!! $model->id !!}">
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="recipes[new][{!! $model->id !!}][recipe_id]"
                   readonly="readonly" value="{!! $model->id !!}">

            <input type="hidden" name="recipes[new][{!! $model->id !!}][main]" value="1" checked="checked">
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->image)
                @include('partials.image', ['src' => $model->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.recipe.show', $model->name, $model->id, ['target' => '_blank']) !!} <span class="lover-case">(@lang('labels.portions'): {!! $model->portions !!})</span>

            <input type="hidden" name="recipes[new][{!! $model->id !!}][image]" value="{!! $model->image !!}">
            <input type="hidden" name="recipes[new][{!! $model->id !!}][name]" value="{!! $model->name !!}">
            <input type="hidden" name="recipes[new][{!! $model->id !!}][recipe_portions]" value="{!! $model->portions !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="recipes[new][{!! $model->id !!}][position]"
                   required="required"
                   value="0">
        </div>
    </td>
    <td class="text-center">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>