<img
        width="{!! $width !!}"
        height="{!! $height !!}"
        class="margin-bottom-5 image_input_thumbnail {!! isset($image_class) ? $image_class : '' !!}"
        src="{!! $image ? $image : 'http://www.placehold.it/' . $width . 'x' . $height . '/EFEFEF/AAAAAA&text=no+image' !!}"
        data-default="{!! 'http://www.placehold.it/' . $width . 'x' . $height . '/EFEFEF/AAAAAA&text=no+image' !!}"
/>

<div class="col-md-12 no-padding">
    <div class="input-group">
        {!! Form::text($name, $image, array_merge(['class' => 'form-control input-sm'], $params)) !!}

        <div class="input-group-addon show-elfinder-button" data-title="@lang('labels.please_select_image')" title="@lang('labels.select_image')" data-target="[elfinder-link='{!! $elfinder_link_name !!}']">
            <i class="fa fa-folder"></i>
        </div>
        <div class="input-group-addon clear-image-button" title="@lang('labels.clear_image')" data-target-image=".{!! $image_class !!}" data-target-input="{!! $target_input !!}">
            <i class="fa fa-close"></i>
        </div>
    </div>
</div>