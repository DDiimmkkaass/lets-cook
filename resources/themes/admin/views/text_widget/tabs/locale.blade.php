<div class="form-group @if ($errors->has($locale.'.title')) has-error @endif">
    {!! Form::label($locale . '[title]', trans('labels.title'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-10">
        {!! Form::text($locale.'[title]', isset($model->translate($locale)->title) ? $model->translate($locale)->title : '', ['placeholder'=> trans('labels.title'), 'class' => 'form-control input-sm title_'.$locale]) !!}

        {!! $errors->first($locale.'.title', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has($locale.'.content')) has-error @endif">
    {!! Form::label($locale . '[content]', trans('labels.content'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-10">
        {!! Form::textarea($locale.'[content]', isset($model->translate($locale)->content) ? $model->translate($locale)->content : '', ['id' => 'content_'.$locale, 'placeholder'=> trans('labels.content'), 'class' => 'form-control input-sm content_'.$locale]) !!}

        {!! $errors->first($locale.'.content', '<p class="help-block error">:message</p>') !!}
    </div>

    @include('partials.tabs.ckeditor', ['id' => 'content_'.$locale])
</div>