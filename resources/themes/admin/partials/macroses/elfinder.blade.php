<div class="col-md-12 no-padding">
    <div class="input-group">
        {!! Form::text($name, $value, array_merge(['class' => 'form-control input-sm'], $params)) !!}

        <div class="input-group-addon show-elfinder-button" data-title="@lang('labels.please_select_image')" data-target="[elfinder-link='{!! $elfinder_link_name !!}']">
            <i class="fa fa-folder"></i>
        </div>
        <div class="input-group-addon download-file-button" data-title="@lang('labels.download')">
            <a target="_blank"
               data-href="{!! route('admin.download.file', ['file']) !!}"
               href="{!! route('admin.download.file', ['file' => $value]) !!}">
                <i class="fa fa-download" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>

<div class="clearfix"></div>