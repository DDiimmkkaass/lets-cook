@foreach (config('app.locales') as $key => $locale)
    <div class="form-group required @if ($errors->has($locale.'.title')) has-error @endif">
        {!! Form::label($locale . '[title]', trans('labels.title'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-6 col-md-6">
            {!! Form::text($locale.'[title]', isset($model->translate($locale)->title) ? $model->translate($locale)->title : '', ['placeholder'=> trans('labels.title'), 'required' => true, 'class' => 'form-control input-sm title_'.$locale]) !!}

            {!! $errors->first($locale.'.title', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@endforeach

<div class="form-group required @if ($errors->has('layout_position')) has-error @endif">
    {!! Form::label('layout_position', trans('labels.position_on_site'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::select('layout_position', $layout_positions, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('layout_position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('template')) has-error @endif">
    {!! Form::label('template', trans('labels.template'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('template', $templates, old($model->template) ?: ($model->template ?: '_default'), ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('template', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('show_title')) has-error @endif">
    {!! Form::label('show_title', trans('labels.show_title'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('show_title', ['1' => trans('labels.yes'), '0' => trans('labels.no')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('show_title', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('position', $model->position ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
    <p class="help-block">@lang('messages.banner position helper text')</p>
</div>