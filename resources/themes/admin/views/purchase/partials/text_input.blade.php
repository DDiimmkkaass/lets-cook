<div class="col-sm-6 col-sm-push-3">
    <input class="input-sm form-control ajax-field-changer {!! $field !!}-text-input"
           type="text"
           data-id="{!! $model->id !!}"
           data-token="{!! csrf_token() !!}"
           data-url="{!! isset($url) ? $url : route('admin.purchase.ajax_field', $model->id) !!}"
           data-field="{!! $field !!}"
           value="{!! ($model->{$field}) !!}"
            >
</div>
