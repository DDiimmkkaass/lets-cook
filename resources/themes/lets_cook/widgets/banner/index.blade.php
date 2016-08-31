@if (count($banners))
    @foreach($banners as $banner)
        {!! $banner !!}
    @endforeach
@endif