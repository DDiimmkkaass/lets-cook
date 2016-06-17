<div class="status-button">
    <input type="checkbox" class="status-toggler {!! $field !!}-checkbox-input"
           data-id="{!! $model->id !!}"
           data-token="{!! csrf_token() !!}"
           data-field="{!! $field !!}"
           data-url="{!! route('admin.' . $type . '.ajax_field', ['id' => $model->id]) !!}"
           data-value="{!! $model->{$field} == true ? false : true !!}"
            {!! ($model->{$field} ? 'checked="checked"' : '') !!} />
</div>
