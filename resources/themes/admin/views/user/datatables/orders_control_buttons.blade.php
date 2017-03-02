@if ($user->hasAccess('order.read'))
    <a class="btn btn-primary btn-sm btn-flat" href="{!! route('admin.order.show', $model->id) !!}"
       title="{!! trans('labels.go_to_order') !!}">
        <i class="fa fa-external-link" aria-hidden="true"></i>
    </a>
@endif