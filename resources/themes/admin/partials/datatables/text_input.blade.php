<div class="col-sm-10">
    <input class="input-sm col-xs-16 form-control ajax-field-changer {!! $field !!}-text-input"
           type="text"
           data-id="{!! $model->id !!}"
           data-token="{!! csrf_token() !!}"
           data-url="{!! route('admin.' . $type . '.ajax_field', [$model->id]) !!}"
           data-field="{!! $field !!}"
           value="{!! ($model->{$field}) !!}"
            >
</div>
