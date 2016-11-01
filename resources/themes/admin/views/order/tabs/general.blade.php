<div class="form-group required @if ($errors->has('user_id')) has-error @endif">
    {!! Form::label('order_user_select', trans('labels.user_id'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        <select name="user_id" id="user_id" class="form-control select2 input-sm" aria-hidden="true" required="required">
            <option value="">@lang('labels.please_select')</option>
            @foreach($users as $user)
                <option value="{!! $user->id !!}"
                        data-full_name="{!! $user->getFullName() !!}"
                        data-email="{!! $user->email !!}"
                        data-phone="{!! $user->phone !!}"
                        data-additional_phone="{!! $user->additional_phone !!}"
                        data-address="{!! $user->address !!}"
                        data-city_id="{!! $user->city_id ? $user->city_id : '' !!}"
                        data-city_name="{!! $user->city_name !!}"
                        data-comment="{!! $user->comment !!}"
                        data-link="{!! route('admin.user.edit', $user->id) !!}"
                        @if ($user->id == $model->user_id || $user->id == old('user_id')) selected="selected" @endif
                >
                    {!! $user->getFullName() !!}
                </option>
            @endforeach
        </select>
        <input type="hidden" id="old_user" value="{!! $model->user_id !!}">

        {!! $errors->first('user_id', '<p class="help-block error">:message</p>') !!}
    </div>

    <a id="order_user_link"
       target="_blank"
       class="display-block margin-top-5"
       href="@if ($model->exists && $model->user_id){!! route('admin.user.edit', $model->user_id) !!}@else#@endif"
       title="@lang('labels.go_to_user')">
        @lang('labels.go_to_user')
    </a>
</div>

<div class="form-group required @if ($errors->has('full_name')) has-error @endif">
    {!! Form::label('full_name', trans('labels.full_name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('full_name', null, ['id' => 'full_name', 'placeholder' => trans('labels.full_name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('full_name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('email', null, ['id' => 'email', 'placeholder' => trans('labels.email'), 'class' => 'form-control input-sm', 'required' => true]) !!}

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

<div class="form-group required @if ($errors->has('payment_method')) has-error @endif">
    {!! Form::label('payment_method', trans('labels.payment_method'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('payment_method', $payment_methods, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('payment_method', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', $statuses, null, ['class' => 'form-control select2 input-sm order-status-select', 'aria-hidden' => 'true', 'required' => true]) !!}
        {!! Form::hidden('old_status', old('old_status') ?: $model->status) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div id="status-comment-block" class="form-group @if ($errors->has('status_comment')) has-error @endif hidden">
    {!! Form::label('status_comment', trans('labels.status_comment'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-7">
        {!! Form::textarea('status_comment', null, ['rows' => '2', 'placeholder' => trans('labels.status_comment_placeholder'), 'class' => 'form-control input-sm height-auto']) !!}

        {!! $errors->first('status_comment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('verify_call')) has-error @endif">
    {!! Form::label('verify_call', trans('labels.verify_call'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('verify_call', ['0' => trans('labels.no'), '1' => trans('labels.yes')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}

        {!! $errors->first('verify_call', '<p class="help-block error">:message</p>') !!}
    </div>
</div>