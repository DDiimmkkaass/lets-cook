<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), array('class' => "control-label")) !!}

    {!! Form::text('name', null, array('placeholder' => trans('labels.name'), 'class' => 'form-control input-sm')) !!}
    {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
</div>
