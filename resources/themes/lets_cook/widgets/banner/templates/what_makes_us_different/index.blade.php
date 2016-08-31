@if ($banner->visible_items->count())
    <section class="our-diff {!! $banner->class !!}">
        @if ($banner->show_title)
            <h2 class="our-diff__heading">{!! $banner->title !!}</h2>
        @endif

        <ul class="our-diff__list">
            @foreach($banner->visible_items as $item)
                <li class="our-diff__item">
                    <img src="{!! thumb($item->image, 220, 120) !!}" alt="{!! $item->title !!}" class="our-diff__img">
                    <h3 class="our-diff__title">{!! $item->title !!}</h3>
                    <div class="our-diff__desc">
                        {!! $item->text !!}
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif