@if ($banner->visible_items->count())
    <section class="how-works__content how-works-content {!! $banner->class !!}">
        <ul class="how-works-content__list">
            @foreach($banner->visible_items as $key => $item)
                <li class="how-works-content__item">
                    <div class="how-works-content__wrapper">
                        <div class="how-works-content__number">{!! $key + 1 !!}</div>

                        <div class="how-works-content__main">
                            <div class="how-works-content__img"
                                 style="background-image: url({!! thumb($item->image, 176) !!});">
                            </div>

                            <div class="how-works-content__right">
                                <h2 class="how-works-content__title">
                                    {!! $item->title !!}
                                </h2>

                                <div class="how-works-content__desc">
                                    {!! $item->text !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif