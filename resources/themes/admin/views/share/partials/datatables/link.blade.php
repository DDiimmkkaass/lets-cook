@if ($model->link)
    {!! $model->link !!}
    <a class="cursor-pointer margin-left-10"
       title="@lang('labels.go_to_page')"
       href="{!! $model->link !!}"
       target="_blank">
        <i class="fa fa-external-link"></i>
    </a>
@endif