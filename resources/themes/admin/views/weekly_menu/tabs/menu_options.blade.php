<div class="recipes-add-control">
    <div class="form-group no-margin @if ($errors->first('week') || $errors->first('started_at') || $errors->first('ended_at')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left no-padding">
            @lang('labels.week')
        </div>
        <div class="input-group margin-bottom-10 col-xs-12 col-sm-4 col-md-3 col-lg-2">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            {!! Form::text('week', old('week') ?: $model->getWeekDates(), ['id' => 'week_menu_date', 'class' => 'form-control pull-left input-sm', 'required' => true]) !!}
        </div>

        {!! $errors->first('week', '<p class="help-block error position-relative">:message</p>') !!}
        {!! $errors->first('started_at', '<p class="help-block error position-relative">:message</p>') !!}
        {!! $errors->first('ended_at', '<p class="help-block error position-relative">:message</p>') !!}
    </div>
</div>