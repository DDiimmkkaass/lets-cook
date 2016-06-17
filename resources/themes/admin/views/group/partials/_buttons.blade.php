<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3">
        <a href="{!! route('admin.group.index') !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
    </div>

    <div class="col-md-4 pull-right ta-right">
        {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat btn-save')) !!}
    </div>
</div>