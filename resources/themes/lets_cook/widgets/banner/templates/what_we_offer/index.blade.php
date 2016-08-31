@if ($banner->visible_items->count())
    <section class="about-food {!! $banner->class !!}">
        <ul class="about-food__list">
            @foreach($banner->visible_items as $item)
                <li class="about-food__item">
                    <div class="about-food__img"
                         style="background-image: url({!! thumb($item->image, 172) !!})"></div>
                    <div class="about-food__title">{!! $item->title !!}</div>
                </li>
            @endforeach
        </ul>
    </section>
@endif