@if (empty($src))
    <?php $src = $no_image; ?>
@endif

@if (empty($attributes['width']))
    <?php $attributes['width'] = 100; ?>
@endif

@if (empty($attributes['height']))
    <?php $attributes['height'] = 100; ?>
@endif

<img src="{!! thumb($src, $attributes['width'], $attributes['height']) !!}"
    @if (!empty($attributes) && count('attributes'))
        @foreach($attributes as $key => $value)
            {!! $key !!}="{!! $value !!}"
        @endforeach
    @endif
/>