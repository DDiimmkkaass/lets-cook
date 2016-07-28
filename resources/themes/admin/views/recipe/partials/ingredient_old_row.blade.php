<tr id="{!! $key !!}_{!! $ingredient->ingredient_id !!}">
    <td>
        <div class="form-group @if ($errors->has($key.'.old.' .$ingredient->id. '.ingredient_id')) has-error @endif">
            {!! Form::text($key.'[old][' .$ingredient->id. '][ingredient_id]', $ingredient->ingredient_id, ['id' => $key.'.old.' .$ingredient->id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}
            {!! Form::hidden($key.'[old][' .$ingredient->id. '][type]', $ingredient->type) !!}
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($ingredient->ingredient->image)
                @include('partials.image', ['src' => $ingredient->ingredient->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.ingredient.show', $ingredient->ingredient->name, [$model->id], ['target' => '_blank']) !!}
            ({!! $ingredient->ingredient->unit->name !!})
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has($key.'.old.' .$ingredient->id. '.count')) has-error @endif">
            {!! Form::text($key.'[old][' .$ingredient->id. '][count]', $ingredient->count ?: 1, ['id' => $key.'.old.' .$ingredient->id. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
        </div>
    </td>
    @if ($ingredient->getStringType() == 'normal')
        <td class="text-center">
            <div class="form-group @if ($errors->has($key.'.old.' .$ingredient->id. '.main')) has-error @endif">
                <label for=$key.".old.{!! $ingredient->id !!}.main" class="checkbox-label">
                    {!! Form::radio('main_ingredient', $ingredient->ingredient_id, $ingredient->main, ['id' => $key.'.old.' .$ingredient->id. '.main', 'class' => 'square main-ingredient']) !!}
                </label>
            </div>
        </td>
    @endif
    <td>
        <div class="form-group required @if ($errors->has($key.'.old.' .$ingredient->id. '.position')) has-error @endif">
            {!! Form::text($key.'[old][' .$ingredient->id. '][position]', $ingredient->position ?: 0, ['id' => $key.'.old.' .$ingredient->id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
        </div>
    </td>

    <td class="text-center coll-actions">
        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $ingredient->id !!}"
           data-name="{!! $key !!}[remove][]"><i class="fa fa-remove"></i></a>
    </td>
</tr>