<div class="form-group @if ($errors->has('coupon_id')) has-error @endif">
    {!! Form::label('coupon_id', trans('labels.coupon'), ['class' => 'control-label col-sm-2']) !!}

    <div class="col-sm-3">
        {!! Form::select('coupon_id', $user_coupons, old('coupon_id') ?: null, ['id' => 'coupon_id', 'class' => 'form-control input-sm select2 coupon-select']) !!}

        {!! $errors->first('coupon_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('coupon_code')) has-error @endif">
    {!! Form::label('coupon_code', trans('labels.coupon_code'), ['class' => 'control-label col-sm-2']) !!}

    <div class="col-sm-3">
        {!! Form::text('coupon_code', old('coupon_code') ?: null, ['id' => 'coupon_code', 'class' => 'form-control input-sm select2 coupon-select']) !!}

        {!! $errors->first('coupon_code', '<p class="help-block error">:message</p>') !!}
    </div>
</div>