@lang('labels.type_of_users'): @lang('labels.discount_users_type_'.$model->getStringUsersType())
<br>
@lang('labels.count'): {!! $model->count ? $model->count : trans('labels.without_limit') !!}
<br>
@lang('labels.users'): {!! $model->users_count ? $model->users_count : trans('labels.without_limit') !!}