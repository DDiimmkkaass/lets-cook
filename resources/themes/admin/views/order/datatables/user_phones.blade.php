<div class="nowrap">{!! $model->phone !!}</div>
@if (!empty($model->additional_phone))
    <div class="nowrap">{!! $model->additional_phone !!}</div>
@endif
@if ($model->user_id && $model->user->phone != $model->phone)
    <div class="nowrap">{!! $model->user->phone !!}</div>
@endif