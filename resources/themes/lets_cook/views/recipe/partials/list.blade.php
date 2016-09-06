<section class="articles-list__main articles-list-main">
    @if (count($list))
        <ul class="articles-list-main__list">
            @foreach($list as $item)
                <li class="articles-list-main__item article-item">
                    <div class="article-item__main">
                        <a href="{!! $item['href'] !!}"
                           title="{!! $item['name'] !!}"
                           class="article-item__img"
                           style="background-image: url({!! $item['image'] !!});"></a>

                        <div class="article-item__content">
                            <a href="{!! $item['href'] !!}"
                               title="{!! $item['name'] !!}"
                               class="article-item__title">{!! $item['name'] !!}</a>

                            @if (count($item['tags']))
                                <ul class="article-item__tag-list">
                                    @foreach($item['tags'] as $tag)
                                        <li class="article-item__tag-item" data-tag="{!! $tag['id'] !!}">
                                            {!! $tag['name'] !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="article-item__description"><span>Ингридиенты:</span>
                                {!! $item['description'] !!}
                            </div>
                        </div>
                    </div>

                    <div class="article-item__additional">
                        <div class="article-item__rating">
                            <div class="article-item__rating-title">рейтинг:</div>
                            <div class="article-item__rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span @if ($i <= $item['rating']) data-active @endif></span>
                                @endfor
                            </div>
                        </div>

                        <div class="article-item__time">
                            <div class="article-item__time-title">время:</div>
                            <div class="article-item__time-value">{!! $item['cooking_time'] !!} минут</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="articles-list-main__loader"></div>
    @endif
</section>