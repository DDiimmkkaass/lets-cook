@if ($banner->visible_items->count())
    <section class="order__you-get order-you-get {!! $banner->class !!}">
        <h2 class="order-you-get__title georgia-title">{!! $banner->title !!}</h2>
        <ul class="order-you-get__list">
            @foreach($banner->visible_items as $item)
                <li class="order-you-get__item">
                    <div class="order-you-get__img"
                         style="background-image: url({!! thumb($item->image, 298, 200) !!});"></div>
                    <div class="order-you-get__desc">
                        {!! $item->text !!}
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif