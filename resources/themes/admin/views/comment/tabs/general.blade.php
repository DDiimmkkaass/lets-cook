<div class="form-group">
    {!! Form::label('commentable_item', trans('labels.commentable_item'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-10">
        <a id="commentable_item" href="{!! $model->getCommentableItemLink() !!}" title="@lang('labels.go_to_item')" class="margin-top-5 display-block">{!! $model->getCommentableItemTitle() !!}</a>
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('comment')) has-error @endif">
    {!! Form::label('comment', trans('labels.comment'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-6 col-md-6">
        {!! Form::textarea('comment', null, ['class' => 'form-control input-sm']) !!}

        {!! $errors->first('comment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>