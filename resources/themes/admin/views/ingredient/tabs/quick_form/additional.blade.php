<div class="form-group margin-bottom-10">
    {!! Form::label('title', trans('labels.name_for_users'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::text('title', null, ['placeholder'=> trans('labels.name_for_users'), 'class' => 'form-control input-sm']) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group required margin-bottom-10">
    {!! Form::label('supplier_id', trans('labels.supplier'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group required margin-bottom-10">
    {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-11 with-after-helper currency-rub padding-left-0">
        {!! Form::text('price', $model->price ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group margin-bottom-10">
    {!! Form::label('sale_price', trans('labels.sale_price'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-11 with-after-helper currency-rub padding-left-0">
        {!! Form::text('sale_price', $model->sale_price ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true']) !!}
    </div>

    <div class="col-xs-12 padding-left-0 padding-right-0">
        <p class="help-block">(@lang('messages.sale price helper message'))</p>
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group margin-bottom-10">
    {!! Form::label('sale_unit_id', trans('labels.sale_units'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::select('sale_unit_id', $units, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group margin-bottom-10">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::imageInput('image', old('image') ? : $model->image) !!}
    </div>

    <div class="clearfix"></div>
</div>