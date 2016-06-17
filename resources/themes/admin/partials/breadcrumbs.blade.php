<ol class="breadcrumb">
    <li class="first">
        <a href="{!! route('admin.home') !!}"><i class="fa fa-home"></i> @lang('labels.home')</a>
    </li>
    @foreach ($breadcrumbs as $i => $data)

        <li {!! ($i == count($breadcrumbs)-1 ? 'class="active"':'') !!}>
            @if ($data['url'])
                <a href="{!! $data['url'] !!}">{!! $data['name'] !!}</a>
            @else
                {!! $data['name'] !!}
            @endif
        </li>
    @endforeach
</ol>

