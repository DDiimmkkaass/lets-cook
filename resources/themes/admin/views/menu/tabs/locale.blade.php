<div class="form-group required @if ($errors->has($locale.'.name')) has-error @endif">
    {!! Form::label($locale . '[name]', trans('labels.name'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-6">
        {!! Form::text($locale.'[name]', isset($model->translate($locale)->name) ? $model->translate($locale)->name : '', ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm name_'.$locale]) !!}

        {!! $errors->first($locale.'.name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>