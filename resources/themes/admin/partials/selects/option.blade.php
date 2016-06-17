@if (is_array($item))
    <option value="{!! $item['id'] !!}">{!! $item['name'] !!}</option>
@else
    <option value="{!! $item->id !!}">{!! $item->name !!}</option>
@endif