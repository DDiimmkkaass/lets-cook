<div class="form-group required @if ($errors->has('delivery_date')) has-error @endif">
    {!! Form::label('delivery_date', trans('labels.delivery_date'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        <div class="input-group">
            {!! Form::text('delivery_date', null, ['placeholder' => trans('labels.delivery_date'), 'class' => 'form-control input-sm inputmask-delivery_date datepicker-delivery_date']) !!}
            {!! Form::hidden('old_delivery_date', $model->delivery_date) !!}
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>

        {!! $errors->first('delivery_date', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('delivery_time')) has-error @endif">
    {!! Form::label('delivery_time', trans('labels.delivery_time'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('delivery_time', $delivery_times, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('delivery_time', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('city_id')) has-error @endif">
    {!! Form::label('city_id', trans('labels.city_id'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::select('city_id', $cities, null, ['class' => 'form-control select2 input-sm order-city-id-select', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('city_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div id="order-city-block" class="form-group @if ($errors->has('city_name')) has-error @endif @if (!empty($model->city_id)) hidden @endif">
    {!! Form::label('city_name', trans('labels.enter_a_city'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::text('city_name', null, ['placeholder' => trans('labels.city'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('city_name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('address')) has-error @endif">
    {!! Form::label('address', trans('labels.address'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        {!! Form::text('address', null, ['placeholder' => trans('labels.address'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('address', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('comment')) has-error @endif">
    {!! Form::label('comment', trans('labels.order_comments'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('comment', null, ['rows' => '3', 'placeholder' => trans('labels.order_comments'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('comment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>