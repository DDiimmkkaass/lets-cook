@foreach($parameters as $key => $parameter)
    <div class="form-group @if ($errors->has('additional_parameter')) has-error @endif">
        <div class="col-xs-12">
            <label for="parameters_{!! $key !!}" class="checkbox-label margin-left-10">
                {!! Form::radio('additional_parameter', $parameter->id, isset($selected_parameters[$parameter->id]) ? true : false, ['id' => 'additional_parameter_'.$key, 'class' => 'square']) !!}
                <span class="title">{!! $parameter->name !!}</span>
            </label>
        </div>
    </div>
@endforeach

@if ($errors->has('additional_parameter'))
    <div class="form-group has-error">
        <div class="col-xs-12">
            {!! $errors->first('additional_parameter', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@endif