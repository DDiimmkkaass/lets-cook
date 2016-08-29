<div class="form-group required @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-5">
        {!! Form::text('name', null, ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

@if ($type == 'additional')
    <div class="form-group required @if ($errors->has('price')) has-error @endif">
        {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-2 col-md-1 with-after-helper currency-rub">
            {!! Form::text('price', $model->price ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

            {!! $errors->first('price', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@endif

<div class="form-group required @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-2">
        {!! Form::text('position', $model->position ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', trans('labels.description'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('description', null, ['id' => 'description', 'rows' => '3', 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('description', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'description'])