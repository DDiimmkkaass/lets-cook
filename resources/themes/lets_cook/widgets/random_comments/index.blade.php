@if ($list->count())
    <section class="clients-reviews">
        <h2 class="clients-reviews__title">отзывы клиентов</h2>

        <ul class="clients-reviews__list">
            @foreach($list as $item)
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img"
                         style="background-image: url({!! thumb($item->getUserImage(), 100) !!});">
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">{!! $item->getUserName() !!}</div>
                        <div class="review-item__comment">{!! $item->comment !!}</div>
                        <div class="review-item__date">{!! $item->getDate() !!}</div>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif