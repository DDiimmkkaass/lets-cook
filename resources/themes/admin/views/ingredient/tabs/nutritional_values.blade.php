@foreach($nutritional_values as $value)

    <div class="form-group @if ($errors->has('nutritional_values['.$value->id.']')) has-error @endif">
        {!! Form::label('nutritional_values_'.$value->id.'_value', $value->name, ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-2 col-md-1">
            {!! Form::hidden('nutritional_values['.$value->id.'][id]', $value->id) !!}
            {!! Form::text('nutritional_values['.$value->id.'][value]', isset($ingredient_nutritional_values[$value->id]) ? $ingredient_nutritional_values[$value->id]['value'] : 0, ['id' => 'nutritional_values_'.$value->id.'_value', 'class' => 'form-control input-sm', 'aria-hidden' => 'true']) !!}

            {!! $errors->first('nutritional_values['.$value->id.'][id]', '<p class="help-block error">:message</p>') !!}
            {!! $errors->first('nutritional_values['.$value->id.'][value]', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

@endforeach