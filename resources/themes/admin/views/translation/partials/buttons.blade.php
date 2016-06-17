<div class="col-md-3">
    <a href="{!! route('admin.translation.index', $group) !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
</div>

@if ($user->hasAccess('translation.write'))
    <div class="col-md-4 pull-right ta-right">
        {!! Form::button(trans('labels.save'), ['class' => 'btn btn-success btn-flat with-loading']) !!}
    </div>
@endif