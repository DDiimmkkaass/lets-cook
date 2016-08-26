@if (is_array($item))
    <option value="{!! $item['id'] !!}"
            @if (isset($item['attributes']))
                @foreach($item['attributes'] as $attribute => $value)
                    {!! $attribute !!}="{!! $value !!}"
                @endforeach
            @endif
    >
        {!! $item['name'] !!}
    </option>
@else
    <option value="{!! $item->id !!}">{!! $item->name !!}</option>
@endif