<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), ['class' => "control-label"]) !!}

    {!! Form::text('name', null, ['placeholder' => trans('labels.name'), 'class' => 'form-control input-sm']) !!}
    {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
</div>
