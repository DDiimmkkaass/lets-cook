<section class="articles-list__main articles-list-main">
    @if (count($list))
        <ul class="articles-list-main__list">
            @foreach($list as $item)
                <li class="articles-list-main__item article-item">
                    <div class="article-item__main">
                        <a title="{!! $item['name'] !!}"
                           href="{!! $item['href'] !!}"
                           class="article-item__img"
                           style="background-image: url({!! $item['image'] !!});"></a>

                        <div class="article-item__content">
                            <a href="{!! $item['href'] !!}" class="article-item__title">{!! $item['name'] !!}</a>

                            @if (count($item['tags']))
                                <ul class="article-item__tag-list">
                                    @foreach($item['tags'] as $tag)
                                        <li class="article-item__tag-item" data-tag="{!! $tag['id'] !!}">
                                            {!! $tag['name'] !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="article-item__description">
                                {!! $item['description'] !!}
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="articles-list-main__loader"></div>
    @endif
</section>