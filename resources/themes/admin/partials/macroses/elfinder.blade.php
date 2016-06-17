<div class="col-md-12 no-padding">
    <div class="input-group">
        {!! Form::text($name, $value, array_merge(['class' => 'form-control input-sm'], $params)) !!}

        <div class="input-group-addon show-elfinder-button" data-title="@lang('labels.please_select_image')" data-target="[elfinder-link='{!! $elfinder_link_name !!}']">
            <i class="fa fa-folder"></i>
        </div>
    </div>
</div>