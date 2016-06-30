<tr id="ingredient_{!! $model->id !!}">
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="ingredients[new][{!! $model->id !!}][ingredient_id]"
                   readonly="readonly" value="{!! $model->id !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($model->image)
                @include('partials.image', ['src' => $model->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! $model->name !!} ({!! $model->unit->name !!})

            <input type="hidden" name="ingredients[new][{!! $model->id !!}][image]" value="{!! $model->image !!}">
            <input type="hidden" name="ingredients[new][{!! $model->id !!}][name]" value="{!! $model->name !!}">
            <input type="hidden" name="ingredients[new][{!! $model->id !!}][unit]" value="{!! $model->unit->name !!}">
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="ingredients[new][{!! $model->id !!}][count]"
                   required="required"
                   value="1">
        </div>
    </td>
    <td>
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="ingredients[new][{!! $model->id !!}][position]"
                   required="required"
                   value="0">
        </div>
    </td>
    <td class="text-center">
        <div class="form-group">
            <label for="ingredient.new.{!! $model->id !!}.main" class="checkbox-label">
                <input type="radio"
                       name="main_ingredient"
                       value="{!! $model->id !!}"
                       id="ingredient.new.{!! $model->id !!}.main"
                       class="square main-ingredient">
            </label>
        </div>
    </td>
    <td class="text-center">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>