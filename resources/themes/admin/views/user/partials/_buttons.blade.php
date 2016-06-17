<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3">
        <a href="{!! empty($back_url) ? route('admin.user.index') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
    </div>


    <div class="col-md-4 pull-right ta-right">
        @if ($user->hasAccess('user.write'))
            @if (!isset($without_password_change))
                <a href="{!! route('admin.user.new_password.get', $model->id) !!}" class="btn btn-sm btn-warning margin-right btn-flat">{!! trans("labels.change_password") !!}</a>
            @endif
        @endif

        @if ($user->hasAccess('user.write') || $user->hasAccess('user.create'))
            {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat')) !!}
        @endif
    </div>
</div>
