<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', trans('labels.name_for_users'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-5">
        {!! Form::text('title', null, ['placeholder'=> trans('labels.name_for_users'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('title', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('supplier_id')) has-error @endif">
    {!! Form::label('supplier_id', trans('labels.suppliers'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('supplier_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-1 with-after-helper currency-rub">
        {!! Form::text('price', $model->price ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('price', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
    </div>
</div>