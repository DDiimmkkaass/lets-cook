@if ($user->hasAccess('weekly_menu.read'))
    <a class="btn btn-default btn-sm btn-flat" href="{!! route('admin.weekly_menu.show', [$model->id]) !!}"
       title="{!! trans('labels.show') !!}">
        <i class="fa fa-eye"></i>
    </a>&nbsp;
    @unless ($model->old())
        <a class="btn btn-info btn-sm btn-flat" href="{!! route('admin.weekly_menu.edit', [$model->id]) !!}"
           title="{!! trans('labels.edit') !!}">
            <i class="fa fa-pencil"></i>
        </a>
    @endunless
@endif