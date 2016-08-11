<div class="form-group required margin-bottom-10">
    {!! Form::label('name', trans('labels.name'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::text('name', null, ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm']) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group required margin-bottom-10">
    {!! Form::label('unit_id', trans('labels.units'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::select('unit_id', $units, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
    </div>

    <div class="clearfix"></div>
</div>

<div class="form-group required margin-bottom-10">
    {!! Form::label('category_id', trans('labels.category'), ['class' => 'control-label col-xs-12 padding-left-0 padding-right-0']) !!}

    <div class="col-xs-12 padding-left-0 padding-right-0">
        {!! Form::select('category_id', $categories, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
    </div>

    <div class="clearfix"></div>
</div>