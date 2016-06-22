@foreach($parameters as $key => $parameter)

    <div class="form-group @if ($errors->has('parameters['.$key.']')) has-error @endif">
        {!! Form::label('parameters_'.$key, $parameter->name, ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
            <label for="parameters_{!! $key !!}" class="checkbox-label">
                {!! Form::checkbox('parameters['.$key.']', $parameter->id, isset($selected_parameters[$parameter->id]) ? true : false, ['id' => 'parameters_'.$key, 'class' => 'square']) !!}
            </label>

            {!! $errors->first('parameters['.$key.']', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

@endforeach