<tr id="{!! $key !!}_{!! $id !!}">
    <td>
        <div class="form-group">
            <div class="form-group @if ($errors->has($key.'.new.' .$id. '.ingredient_id')) has-error @endif">
                {!! Form::text($key.'[new][' .$id. '][ingredient_id]', $ingredient->ingredient_id, ['id' => $key.'.new.' .$id. '.ingredient_id', 'class' => 'form-control input-sm', 'readonly' => true]) !!}

                <input type="hidden" name="{!! $key !!}[new][{!! $id !!}][type]" value="{!! get_recipe_ingredient_type_id($type) !!}">
            </div>
        </div>
    </td>
    <td>
        <div class="form-group">
            @if ($ingredient->ingredient->image)
                @include('partials.image', ['src' => $ingredient->ingredient->image, 'attributes' => ['width' => 50, 'class' => 'margin-right-10', 'required' => true]])
            @endif
            {!! link_to_route('admin.ingredient.show', $ingredient->ingredient->name, [$ingredient->ingredient_id], ['target' => '_blank']) !!} ({!! $ingredient->ingredient->unit->name !!})

            {!! Form::hidden($key.'[new][' .$id. '][image]', $ingredient->ingredient->image) !!}
            {!! Form::hidden($key.'[new][' .$id. '][name]', $ingredient->ingredient->name) !!}
            {!! Form::hidden($key.'[new][' .$id. '][unit]', $ingredient->ingredient->unit->name) !!}
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has($key.'.new.' .$id. '.count')) has-error @endif">
            {!! Form::text($key.'[new][' .$id. '][count]', ceil($ingredient->count * $multiplier), ['id' => $key.'.new.' .$id. '.count', 'class' => 'form-control input-sm', 'required' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has($key.'.new.' .$id. '.position')) has-error @endif">
            {!! Form::text($key.'[new][' .$id. '][position]', $ingredient->position, ['id' => $key.'.new.' .$id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
        </div>
    </td>
    <td class="text-center coll-actions">
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </td>
</tr>