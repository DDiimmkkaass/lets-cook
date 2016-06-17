<div class="form-group required @if ($errors->has($locale.'.question')) has-error @endif">
    {!! Form::label($locale . '[question]', trans('labels.question'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-10">
        {!! Form::textarea($locale.'[question]', isset($model->translate($locale)->question) ? $model->translate($locale)->question : '', ['placeholder'=> trans('labels.question'), 'required' => true, 'class' => 'form-control input-sm question_'.$locale]) !!}

        {!! $errors->first($locale.'.question', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has($locale.'.answer')) has-error @endif">
    {!! Form::label($locale . '[answer]', trans('labels.answer'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-10">
        {!! Form::textarea($locale.'[answer]', isset($model->translate($locale)->answer) ? $model->translate($locale)->answer : '', ['placeholder'=> trans('labels.answer'), 'required' => true, 'class' => 'form-control input-sm answer_'.$locale]) !!}

        {!! $errors->first($locale.'.answer', '<p class="help-block error">:message</p>') !!}
    </div>

    @include('partials.tabs.ckeditor', ['id' => $locale . '[answer]'])
</div>