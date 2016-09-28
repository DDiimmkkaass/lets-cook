@if (is_array($item))
    <option value="{!! $item['id'] !!}"
            @if (isset($item['attributes']))
                @foreach($item['attributes'] as $attribute => $value)
                    {!! $attribute !!}="{!! $value !!}"
                @endforeach
            @endif
        @if (!empty($selected)) selected @endif>
        {!! $item['name'] !!}
    </option>
@else
    <option value="{!! $item->id !!}"
            @if (!empty($selected)) selected @endif>
        {!! $item->name !!}
    </option>
@endif