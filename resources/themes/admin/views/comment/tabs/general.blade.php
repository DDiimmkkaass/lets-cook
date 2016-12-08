<div class="form-group required @if ($errors->has('image')) has-error @endif">
    {!! Form::label('name', trans('labels.first_name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-3 col-md-4">
        @if ($model->user_id)
            {!! Form::hidden('user_id', null) !!}
            {!! Form::text('user_name', $model->user->getFullName(), ['readonly' => true,  'class' => 'form-control input-sm']) !!}
        @else
            {!! Form::text('name', null, ['placeholder' => trans('labels.first_name'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
        @endif
    </div>

    @if ($model->exists && $model->user_id)
        <a href="{!! route('admin.user.show', $model->user_id) !!}" class="display-block margin-top-5">{!! trans('labels.go_to_user') !!}</a>
    @endif
</div>

@if (!$model->user_id)
<div class="form-group required @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', old('image') ? : $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('comment')) has-error @endif">
    {!! Form::label('comment', trans('labels.comment'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-6 col-md-6">
        {!! Form::textarea('comment', null, ['rows' => 6, 'class' => 'form-control input-sm auto-height']) !!}

        {!! $errors->first('comment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>