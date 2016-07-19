<div class="form-group required @if ($errors->has('user_id')) has-error @endif">
    {!! Form::label('user_id', trans('labels.user_id'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::select('user_id', $users, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('user_id', '<p class="help-block error">:message</p>') !!}
    </div>

    <a class="display-block margin-top-5" href="{!! route('admin.user.edit', $model->user_id) !!}" title="@lang('labels.go_to_user')">@lang('labels.go_to_user')</a>
</div>

<div class="form-group required @if ($errors->has('full_name')) has-error @endif">
    {!! Form::label('full_name', trans('labels.full_name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('full_name', null, ['placeholder' => trans('labels.full_name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('full_name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('email', null, ['placeholder' => trans('labels.email'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('email', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', trans('labels.phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('phone', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('additional_phone')) has-error @endif">
    {!! Form::label('additional_phone', trans('labels.additional_phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('additional_phone', null, ['placeholder' => trans('labels.additional_phone'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('additional_phone', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('type')) has-error @endif">
    {!! Form::label('type', trans('labels.type'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('type', $types, null, ['class' => 'form-control select2 input-sm order-type-select', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div id="subscribe-period-block" class="form-group required @if ($errors->has('subscribe_period')) has-error @endif @if (!$model->isSubscribe()) hidden @endif">
    {!! Form::label('subscribe_period', trans('labels.subscribe_period'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('subscribe_period', $subscribe_periods, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('subscribe_period', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', $statuses, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('verify_call')) has-error @endif">
    {!! Form::label('verify_call', trans('labels.verify_call'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('verify_call', ['0' => trans('labels.no'), '1' => trans('labels.yes')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}

        {!! $errors->first('verify_call', '<p class="help-block error">:message</p>') !!}
    </div>
</div>