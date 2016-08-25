<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3 pull-right text-right">
        <a href="{!! route('admin.purchase.download', [$list['year'], $list['week']]) !!}" class="btn btn-flat btn-sm btn-success">@lang('labels.download_report') </a>
    </div>
</div>
