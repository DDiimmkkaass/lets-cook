<div class="tab-pane active" id="settings">
    <div class="form-group required @if ($errors->has('full_name')) has-error @endif">
        {!! Form::label('full_name', trans('labels.full_name'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-5">
            {!! Form::text('full_name', null, ['placeholder' => trans('labels.full_name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('full_name', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('email')) has-error @endif">
        {!! Form::label('email', trans('labels.email'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('email', null, ['placeholder' => trans('labels.email'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('email', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('phone')) has-error @endif">
        {!! Form::label('phone', trans('labels.phone'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm inputmask-2', 'required' => true]) !!}

            {!! $errors->first('phone', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('additional_phone')) has-error @endif">
        {!! Form::label('additional_phone', trans('labels.additional_phone'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('additional_phone', null, ['placeholder' => trans('labels.additional_phone'), 'class' => 'form-control input-sm inputmask-2']) !!}

            {!! $errors->first('additional_phone', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('city_id')) has-error @endif">
        {!! Form::label('city_id', trans('labels.city_id'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::select('city_id', $cities, null, ['class' => 'form-control select2 input-sm order-city-id-select', 'aria-hidden' => 'true', 'required' => true]) !!}

            {!! $errors->first('city_id', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div id="order-city-block" class="form-group @if ($errors->has('city_name')) has-error @endif @if (!empty($model->city_id)) hidden @endif">
        {!! Form::label('city_name', trans('labels.enter_a_city'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('city_name', null, ['placeholder' => trans('labels.city'), 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('city_name', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('address')) has-error @endif">
        {!! Form::label('address', trans('labels.address'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('address', null, ['placeholder' => trans('labels.address'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('address', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    @if(empty($model->id))
        <div class="form-group @if ($errors->has('password')) has-error @endif">
            {!! Form::label('password', trans('labels.password'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                {!! Form::text('password', null, ['placeholder' => trans('labels.password'), 'required' => true, 'class' => 'form-control input-sm']) !!}

                {!! $errors->first('password', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>

        <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
            {!! Form::label('password_confirmation', trans('labels.password_confirmation'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                {!! Form::text('password_confirmation', null, ['placeholder' => trans('labels.password_confirmation'), 'required' => true, 'class' => 'form-control input-sm']) !!}

                {!! $errors->first('password_confirmation', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    @endif

    <div class="form-group required @if ($errors->has('activated')) has-error @endif">
        {!! Form::label('activated', trans('labels.activated'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-xs-3">
            {!! Form::select('activated', ['0' => trans('labels.no'), '1' => trans('labels.yes'),], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

            {!! $errors->first('activated', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('gender')) has-error @endif">
        {!! Form::label('gender', trans('labels.gender'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::select('gender', $genders, null, ['class' => 'form-control select2',  'aria-hidden' => 'true']) !!}

            {!! $errors->first('gender', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('birthday')) has-error @endif">
        {!! Form::label('birthday', trans('labels.birthday'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            <div class="input-group">
                {!! Form::text('birthday', null, ['placeholder' => trans('labels.birthday'), 'class' => 'form-control input-sm inputmask-birthday datepicker-birthday']) !!}
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            </div>

            {!! $errors->first('birthday', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('comment', trans('labels.order_comments'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-9">
            {!! Form::textarea('comment', null, ['rows' => '3', 'placeholder' => trans('labels.order_comments'), 'class' => 'form-control input-sm']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('source', trans('labels.source'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-5">
            {!! Form::text('source', null, ['placeholder' => trans('labels.source'), 'class' => 'form-control input-sm']) !!}
        </div>
    </div>

    @foreach($model->socials as $social)
        <div class="form-group">
            {!! Form::label('source', trans('labels.social_network_link'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-5">
                {!! Form::text('', $social->profile_url, ['class' => 'form-control input-sm']) !!}
            </div>
        </div>
    @endforeach

    @if ($model->exists)
        <div class="form-group">
            {!! Form::label('first_order_date', trans('labels.first_order_date'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                {!! Form::text('first_order_date', null, ['class' => 'form-control input-sm', 'readonly' => true]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('success_orders_count', trans('labels.success_orders_count'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                @php($success_orders_count = $model->orders()->finished()->count() + $model->old_site_orders_count)
                {!! Form::text('success_orders_count', $success_orders_count, ['class' => 'form-control input-sm', 'readonly' => true]) !!}
            </div>
        </div>
    @endif

    @include('partials.tabs.fields')

</div>
