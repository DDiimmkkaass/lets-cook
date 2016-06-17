@php($list = isset($list) ? $list : ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')])

<div class="col-sm-10">
    <select class="select2 input-sm col-xs-16 form-control ajax-field-changer {!! $field !!}-select"
               data-id="{!! $model->id !!}"
               data-token="{!! csrf_token() !!}"
               data-url="{!! route('admin.' . $type . '.ajax_field', [$model->id]) !!}"
               data-field="{!! $field !!}"
    >
        @foreach($list as $key => $item)
            <option @if ($model->{$field} == $key) selected="selected" @endif value="{!! $key !!}">{!! $item !!}</option>
        @endforeach
    </select>
</div>
