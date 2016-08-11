@foreach($parameters as $key => $parameter)

    <div class="form-group margin-bottom-10">
        <div class="col-xs-1">
            <label for="parameters_{!! $key !!}" class="checkbox-label">
                {!! Form::checkbox('parameters['.$key.']', $parameter->id, false, ['id' => 'parameters_'.$key, 'class' => 'square']) !!}
            </label>
        </div>

        <div class="col-xs-11 padding-left-0 padding-right-0">
            <b>{!! $parameter->name !!}</b>
        </div>

        <div class="clearfix"></div>
    </div>

@endforeach