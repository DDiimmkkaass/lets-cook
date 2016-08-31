<div class="all-news-articles__item" data-order="2">
    @foreach($list as $item)
        <h3 class="all-news-articles__title">{!! $item->title !!}</h3>
        <div class="all-news-articles__desc">
            {!! str_limit($item->short_content, 95,'') !!}
        </div>
    @endforeach
    <a href="{!! route('articles.index') !!}" class="all-news-articles__link yellow-long-button">
        Посмотреть все статьи
    </a>
</div>