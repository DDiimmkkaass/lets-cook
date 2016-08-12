<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3">
        <a href="{!! empty($back_url) ? route('admin.recipe.index') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
    </div>

    @if ($user->hasAccess('recipe.write') || $user->hasAccess('recipe.create'))
        <div class="col-md-9 pull-right ta-right">
            @if ($model->draft || isset($copy) || !$model->exists)
                <a id="draft_submit" class="btn btn-primary btn-flat btn-xs margin-right-10" href="#">@lang('labels.save_draft')</a>
            @endif

            {!! Form::submit(trans('labels.save'), ['class' => 'btn btn-success btn-flat']) !!}
        </div>
    @endif
</div>
