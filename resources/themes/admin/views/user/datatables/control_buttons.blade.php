@include('partials.datatables.control_buttons', ['type' => 'user'])

@if ($user->hasAccess('user.login_as_user'))
    <a class="btn btn-primary btn-sm btn-flat" href="{!! route('auth.admin_login', $model->id) !!}"
       title="{!! trans('labels.login_as_user') !!}">
        <i class="fa fa-external-link" aria-hidden="true"></i>
    </a>
@endif