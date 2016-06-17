<div class="form-group required @if ($errors->has($locale.'.name')) has-error @endif">
    {!! Form::label($locale . '[name]', trans('labels.name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-10">
        {!! Form::text($locale.'[name]', isset($model->translate($locale)->name) ? $model->translate($locale)->name : '', ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm name_'.$locale]) !!}

        {!! $errors->first($locale.'.name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has($locale . '[short_content]')) has-error @endif">
    {!! Form::label($locale . '[short_content]', trans('labels.short_content'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea($locale . '[short_content]', isset($model->translate($locale)->short_content) ? $model->translate($locale)->short_content : '', ['rows' => '3', 'placeholder' => trans('labels.short_content'), 'class' => 'form-control input-sm short_content' . $locale]) !!}

        {!! $errors->first($locale . '[short_content]', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => $locale . '[short_content]'])

<div class="form-group @if ($errors->has($locale . '[content]')) has-error @endif">
    {!! Form::label($locale . '[content]', trans('labels.content'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea($locale . '[content]', isset($model->translate($locale)->content) ? $model->translate($locale)->content : '', ['rows' => '3', 'placeholder' => trans('labels.content'), 'class' => 'form-control input-sm content_' . $locale]) !!}

        {!! $errors->first($locale . '[content]', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => $locale . '[content]'])

<div class="form-group @if ($errors->has($locale.'.meta_title')) has-error @endif">
    {!! Form::label($locale . '[meta_title]', trans('labels.meta_title'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-10">
        {!! Form::text($locale.'[meta_title]', isset($model->translate($locale)->meta_title) ? $model->translate($locale)->meta_title : '', ['placeholder' => trans('labels.meta_title'), 'class' => 'form-control input-sm meta_title_'.$locale]) !!}

        {!! $errors->first($locale.'.meta_title', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has($locale.'.meta_description')) has-error @endif">
    {!! Form::label($locale . '[meta_description]', trans('labels.meta_description'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9 col-md-10">
        {!! Form::text($locale.'[meta_description]', isset($model->translate($locale)->meta_description) ? $model->translate($locale)->meta_description : '', ['placeholder' => trans('labels.meta_description'), 'class' => 'form-control input-sm meta_description_'.$locale]) !!}

        {!! $errors->first($locale.'.meta_description', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has($locale.'.meta_keywords')) has-error @endif">
    {!! Form::label($locale . '[meta_keywords]', trans('labels.meta_keywords'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9 col-md-10">
        {!! Form::text($locale.'[meta_keywords]', isset($model->translate($locale)->meta_keywords) ? $model->translate($locale)->meta_keywords : '', ['placeholder' => trans('labels.meta_keywords'), 'class' => 'form-control input-sm meta_keywords_'.$locale]) !!}

        {!! $errors->first($locale.'.meta_keywords', '<p class="help-block error">:message</p>') !!}
    </div>
</div>