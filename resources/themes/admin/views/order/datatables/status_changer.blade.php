<div class="status-changer">
    <span class="label lover-case label-{!! $model->getStringStatus() !!}">
        @lang('labels.order_status_'.$model->getStringStatus())
    </span>
    @if ($model->isStatus('changed'))
        <div class="status-changer-buttons margin-top-10">
            <a href="#"
               title="@lang('labels.make_order_paid')"
               data-status="paid"
               data-status_label="@lang('labels.order_status_paid')"
               data-order_id="{!! $model->id !!}"
               data-_token="{!! csrf_token() !!}"
               class="change-status btn btn-flat btn-xs btn-success">
                <i class="fa fa-check" aria-hidden="true"></i>
            </a>
            <a href="#"
               title="@lang('labels.make_order_deleted')"
               data-status="deleted"
               data-status_label="@lang('labels.order_status_deleted')"
               data-order_id="{!! $model->id !!}"
               data-_token="{!! csrf_token() !!}"
               class="change-status btn btn-flat btn-xs btn-danger">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
        </div>
    @endif
</div>