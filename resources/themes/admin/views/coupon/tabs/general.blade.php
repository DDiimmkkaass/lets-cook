@unless ($model->exists)
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-sm-6">
                <div class="form-group @if ($errors->has('create_count')) has-error @endif">
                    {!! Form::label('create_count', trans('labels.count_to_create'), ['class' => 'control-label col-xs-12 col-sm-6 col-md-4']) !!}

                    <div class="col-xs-12 col-sm-6 col-md-8">
                        {!! Form::text('create_count', old('count_to_create') ?: 1, ['placeholder'=> trans('labels.create_count'), 'class' => 'form-control input-sm width-110']) !!}

                        {!! $errors->first('create_count', '<p class="help-block error">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group @if ($errors->has('codes')) has-error @endif">
                    {!! Form::label('codes', trans('labels.codes'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

                    <div class="col-xs-12 col-sm-9 col-md-10">
                        {!! Form::textarea('codes', old('codes') ?: '', ['placeholder'=> trans('labels.codes'), 'class' => 'form-control input-sm height-auto']) !!}

                        {!! $errors->first('codes', '<p class="help-block error">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endunless

<div class="form-group required @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-5">
        {!! Form::text('name', null, ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('discount')) has-error @endif">
    {!! Form::label('discount', trans('labels.discount'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('discount', $model->discount ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('discount', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('discount_type')) has-error @endif">
    {!! Form::label('discount_type', trans('labels.discount_type'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('discount_type', $discount_types, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('discount_type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('type')) has-error @endif">
    {!! Form::label('type', trans('labels.baskets_type'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('type', $types, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('count')) has-error @endif">
    {!! Form::label('count', trans('labels.number_of_uses'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('count', old('count') ?: 1, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('count', '<p class="help-block error">:message</p>') !!}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 margin-top-4">
        <p class="help-block">(@lang('messages.discount count helper message'))</p>
    </div>
</div>

<div class="form-group required @if ($errors->has('users_count')) has-error @endif">
    {!! Form::label('users_count', trans('labels.users_count'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('users_count', old('users_count') ?: 1, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('users_count', '<p class="help-block error">:message</p>') !!}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 margin-top-4">
        <p class="help-block">(@lang('messages.discount count helper message'))</p>
    </div>
</div>

<div class="form-group required @if ($errors->has('users_type')) has-error @endif">
    {!! Form::label('users_type', trans('labels.type_of_users'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('users_type', $users_types, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('users_type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('started_at')) has-error @endif">
    {!! Form::label('started_at', trans('labels.started_of_using'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        <div class="input-group">
            {!! Form::text('started_at', null, ['placeholder' => trans('labels.started_of_using'), 'class' => 'form-control input-sm inputmask-birthday datepicker-birthday']) !!}
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>

        {!! $errors->first('started_at', '<p class="help-block error">:message</p>') !!}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 margin-top-4">
        <p class="help-block">(@lang('messages.coupon dates helper message'))</p>
    </div>
</div>

<div class="form-group @if ($errors->has('expired_at')) has-error @endif">
    {!! Form::label('expired_at', trans('labels.expired_of_using'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        <div class="input-group">
            {!! Form::text('expired_at', null, ['placeholder' => trans('labels.expired_of_using'), 'class' => 'form-control input-sm inputmask-birthday datepicker-birthday']) !!}
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>

        {!! $errors->first('expired_at', '<p class="help-block error">:message</p>') !!}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 margin-top-4">
        <p class="help-block">(@lang('messages.coupon dates helper message'))</p>
    </div>
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', trans('labels.description'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('description', null, ['id' => 'description', 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('description', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'description'])
