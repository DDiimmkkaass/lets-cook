@foreach (config('app.locales') as $key => $locale)
    <div class="form-group required @if ($errors->has($locale.'.name')) has-error @endif">
        {!! Form::label($locale . '[name]', trans('labels.name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-7 col-md-10">
            {!! Form::text($locale.'[name]', isset($model->translate($locale)->name) ? $model->translate($locale)->name : '', ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm name_'.$locale]) !!}

            {!! $errors->first($locale.'.name', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@endforeach

<div class="form-group required @if ($errors->has('category_id')) has-error @endif">
    {!! Form::label('category_id', trans('labels.category'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
        {!! Form::select('category_id', $categories, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('category_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>