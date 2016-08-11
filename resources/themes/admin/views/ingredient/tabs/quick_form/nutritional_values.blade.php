@foreach($nutritional_values as $value)

    <div class="form-group margin-bottom-10">
        {!! Form::label('nutritional_values_'.$value->id.'_value', $value->name, ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

        <div class="col-xs-12 padding-left-0 padding-right-0">
            {!! Form::hidden('nutritional_values['.$value->id.'][id]', $value->id) !!}
            {!! Form::text('nutritional_values['.$value->id.'][value]', 0, ['id' => 'nutritional_values_'.$value->id.'_value', 'class' => 'form-control input-sm', 'aria-hidden' => 'true']) !!}
        </div>

        <div class="clearfix"></div>
    </div>

@endforeach