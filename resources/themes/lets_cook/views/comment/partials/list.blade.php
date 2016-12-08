<section class="articles-list__main articles-list-main clients-reviews">
    @if (count($list))
        <ul class="articles-list-main__list clients-reviews__list">
            @foreach($list as $item)
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img"
                         @if (!empty($item['image']))
                            style="background-image: url({!! $item['image'] !!});"
                         @endif>
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">{!! $item['name'] !!}</div>
                        <div class="review-item__comment">{!! $item['comment'] !!}</div>
                        <div class="review-item__date">{!! $item['date'] !!}</div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="articles-list-main__loader"></div>
    @endif
</section>