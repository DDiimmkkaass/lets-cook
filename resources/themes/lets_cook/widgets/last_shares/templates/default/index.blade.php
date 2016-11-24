@if ($list->count())
    <section class="free-offers">
        <ul class="free-offers__mobile">
            @foreach($list as $key => $item)
                @if ($key < 2)
                    <li class="free-offers__mobile-item">
                        <a href="{!! $item->link !!}"
                           class="free-offers__mobile-link"
                           style="background-image: url({!! thumb($item->image, 250, 125) !!});">
                        </a>
                    </li>
                @else
                    @break
                @endif
            @endforeach
            <li class="free-offers__mobile-item">
                <a href="{!! $link !!}"
                   class="free-offers__mobile-link"
                   style="background-image: url({!! theme_asset('images/free-offers/mobile-offer-all.jpg') !!});"></a>
            </li>
        </ul>

        <ul class="free-offers__desktop">
            @foreach($list as $item)
                <li class="free-offers__desktop-item">
                    <a href="{!! $item->link !!}"
                       class="free-offers__desktop-link"
                       style="background-image: url({!! thumb($item->image,  293, 80) !!});">
                    </a>
                </li>
            @endforeach
            <li class="free-offers__desktop-item">
                <a href="{!! $link !!}"
                   class="free-offers__desktop-link"
                   style="background-image: url({!! theme_asset('images/free-offers/desktop-offer-all.jpg') !!});"></a>
            </li>
        </ul>
    </section>
@endif