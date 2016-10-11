<div class="recipes-add-control">
    <div class="form-group no-margin @if ($errors->first('week') || $errors->first('year')) has-error @endif">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.week')
        </div>
        <div class="margin-bottom-10 col-xs-12 col-sm-3 col-md-2 col-lg-1">
            <select name="week" id="week" class="form-control select2 pull-left input-sm" required="required">
                @php($_week = old('week') ?: ($model->week ?: active_week()->weekOfYear))
                @foreach(range(1, 52) as $week)
                    <option @if ($week == $_week) selected="selected" @endif value="{!! $week !!}">@lang('labels.w_label'){!! $week !!}</option>
                @endforeach
            </select>
        </div>
        <div class="margin-bottom-10 col-xs-12 col-sm-3 col-md-2 col-lg-1">
            {!! Form::text('year', old('year') ?: ($model->year ?: Carbon::now()->year), ['id' => 'year_menu_date', 'class' => 'form-control select2 pull-left input-sm', 'required' => true]) !!}
        </div>
        <div class="clearfix"></div>

        {!! $errors->first('week', '<p class="help-block error position-relative">:message</p>') !!}
        {!! $errors->first('year', '<p class="help-block error position-relative">:message</p>') !!}
    </div>
</div>