@if (empty($without_delete))
    {!! Form::open(["route" => ["admin." . $type . ".destroy", $model->id], "id" => "admin_" . $type . "_destroy_form_".$model->id, "method" => "delete", 'class' => 'pull-left']) !!}
@endif

    @if ($user->hasAccess((isset($access) ? $access : $type).'.read'))
        <a class="btn btn-info btn-sm btn-flat" href="{!! route('admin.' . $type . '.edit', [$model->id]) !!}"
           title="{!! trans('labels.edit') !!}">
            <i class="fa fa-pencil"></i>
        </a>&nbsp;
    @endif

    @if ($user->hasAccess((isset($access) ? $access : $type).'.delete') && empty($without_delete))
        <a class="btn btn-danger btn-sm btn-flat"
           href="javascript:void(0);"
           title="{!! trans('labels.delete') !!}"
           onclick="@if (empty($delete_function)) return dialog('{!! trans('labels.deleting_record') !!}', '{!! trans('messages.delete_record') !!}', $(this).closest('form')) @else {!! $delete_function !!} @endif;"
        >
            <i class="fa fa-trash"></i>
        </a>&nbsp;
    @endif

    @if (isset($front_link) && $front_link === true )
        <a class="btn btn-primary btn-sm btn-flat" href="{!! $model->getUrl() !!}" title="@lang('labels.go_to_front')" target="_blank">
            <i class="fa fa-external-link"></i>
        </a>
    @endif

@if (empty($without_delete))
    {!! Form::close() !!}
@endif