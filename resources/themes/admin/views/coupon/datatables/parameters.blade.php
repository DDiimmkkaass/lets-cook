@lang('labels.count'): {!! $model->count ? $model->count : trans('labels.without_limit') !!}
<br>
@lang('labels.users'): {!! $model->users_count ? $model->users_count : trans('labels.without_limit') !!}