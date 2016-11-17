<div class="form-group @if ($errors->has('meta_title')) has-error @endif">
    {!! Form::label('meta_title', trans('labels.meta_title'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::text('meta_title', null, ['placeholder'=> trans('labels.meta_title'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('meta_title', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('meta_description')) has-error @endif">
    {!! Form::label('meta_description', trans('labels.meta_description'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::text('meta_description', null, ['placeholder' => trans('labels.meta_description'), 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('meta_description', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('meta_keywords')) has-error @endif">
    {!! Form::label('meta_keywords', trans('labels.meta_keywords'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::text('meta_keywords', null, ['placeholder'=> trans('labels.meta_keywords'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('meta_keywords', '<p class="help-block error">:message</p>') !!}
    </div>
</div>