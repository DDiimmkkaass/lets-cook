<div class="form-group required @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('labels.slug'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::text('slug', null, ['placeholder' => trans('labels.slug'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('slug', '<p class="help-block error">:message</p>') !!}
    </div>

    <a href="#" class="btn btn-success btn-flat btn-xs margin-top-4 slug-generate">{!! trans('labels.generate') !!}</a>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('position', $model->position ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true']) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>