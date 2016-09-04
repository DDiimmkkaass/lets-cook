<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    @if (empty($generate))
        @if (before_finalisation($list['year'], $list['week']))
            <div class="col-md-3 pull-left text-left">
                <a href="{!! route('admin.purchase.generate', [$list['year'], $list['week']]) !!}" class="btn btn-flat btn-sm btn-primary">@lang('labels.generate_report') </a>
            </div>
        @endif
    @else
        <div class="col-md-3 pull-left text-left">
            <a href="{!! route('admin.purchase.show', [$list['year'], $list['week']]) !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.back_ro_list') </a>
        </div>
    @endif

    <div class="col-md-3 pull-right text-right">
        <a href="{!! route('admin.purchase.'.(empty($generate) ? 'download' : 'download_pre_report' ), [$list['year'], $list['week']]) !!}" class="btn btn-flat btn-sm btn-success">@lang('labels.download_report') </a>
    </div>
</div>
