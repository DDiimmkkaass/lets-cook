<div class="form-group required @if ($errors->has('cooking_time')) has-error @endif">
    {!! Form::label('cooking_time', trans('labels.cooking_time'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-1 with-after-helper units-minutes">
        {!! Form::text('cooking_time', $model->cooking_time ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('cooking_time', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('portions')) has-error @endif">
    {!! Form::label('portions', trans('labels.portions'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-1">
        {!! Form::select('portions', $portions, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('portions', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('recipe')) has-error @endif">
    {!! Form::label('recipe', trans('labels.recipe'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('recipe', null, ['id' => 'recipe', 'rows' => '3', 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('recipe', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'recipe'])

<div class="form-group @if ($errors->has('helpful_hints')) has-error @endif">
    {!! Form::label('helpful_hints', trans('labels.helpful_hints'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('helpful_hints', null, ['id' => 'helpful_hints', 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('helpful_hints', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'helpful_hints'])

<div class="form-group @if ($errors->has('home_equipment')) has-error @endif">
    {!! Form::label('home_equipment', trans('labels.home_equipment'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('home_equipment', null, ['id' => 'home_equipment', 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('home_equipment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'home_equipment'])