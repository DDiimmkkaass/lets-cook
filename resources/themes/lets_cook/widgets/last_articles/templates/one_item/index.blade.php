<div class="all-news-articles__item" data-order="2">
    @foreach($list as $item)
        <h3 class="all-news-articles__title">{!! strip_tags($item->title) !!}</h3>
        <div class="all-news-articles__desc">
            {!! str_limit($item->getShortContent(), 95,'') !!}
        </div>
    @endforeach
    <a href="{!! route('articles.index') !!}" class="all-news-articles__link yellow-long-button">
        Посмотреть все статьи
    </a>
</div>