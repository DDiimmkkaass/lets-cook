<div class="form-group required @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-5">
        {!! Form::text('name', null, ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('package')) has-error @endif">
    {!! Form::label('package', trans('labels.package'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9 col-md-10">
        <div class="col-xs-6 col-sm-4 col-md-2 padding-left-0">
            <div class="checkbox-label">
                {!! Form::radio('package', 1, null, ['id' => 'package', 'class' => 'square']) !!}

                <span class="title">{!! trans('labels.package') !!} 1</span>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="checkbox-label">
                {!! Form::radio('package', 2, null, ['id' => 'package', 'class' => 'square']) !!}

                <span class="title">{!! trans('labels.package') !!} 2</span>
            </div>
        </div>

        <div class="clearfix"></div>

        {!! $errors->first('package', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('position', $model->position ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>