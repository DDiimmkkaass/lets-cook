@php($weekdays = [
    '1' => trans_choice('labels.day_of_week_to_string', 1),
    '2' => trans_choice('labels.day_of_week_to_string', 2),
    '3' => trans_choice('labels.day_of_week_to_string', 3),
    '4' => trans_choice('labels.day_of_week_to_string', 4),
    '5' => trans_choice('labels.day_of_week_to_string', 5),
    '6' => trans_choice('labels.day_of_week_to_string', 6),
    '0' => trans_choice('labels.day_of_week_to_string', 0),
])

<div class="row form-group">
    <div class="col-xs-12 col-sm-6 col-md-3">
        {!! Form::select('value', $weekdays, null, ['id' => 'value', 'required' => true, 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}
    </div>
</div>