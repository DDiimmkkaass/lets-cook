<div class="form-group required @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-5">
        {!! Form::text('name', null, ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('priority')) has-error @endif">
    {!! Form::label('priority', trans('labels.priority'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('priority', $model->priority ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('priority', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('comments')) has-error @endif">
    {!! Form::label('comments', trans('labels.comments'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9 col-md-10">
        {!! Form::textarea('comments', null, ['rows' => 5, 'placeholder'=> trans('labels.comments'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('comments', '<p class="help-block error">:message</p>') !!}
    </div>
</div>