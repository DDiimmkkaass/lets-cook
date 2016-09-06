<section class="articles-list__pagination articles-list-pag">
    <div class="articles-list-pag__all" data-active>Все</div>

    <ul class="articles-list-pag__list">
        <li class="articles-list-pag__item" data-pagination="prev">
            <span data-device="mobile">Пред. {!! count($list) !!}</span>
            <span data-device="desktop">
                предыдущие {!! count($list) !!} @choice('front_labels.count_of_news', count($list))
            </span>
        </li>

        @if ($next_count)
            <li class="articles-list-pag__item" data-pagination="next" data-active>
                <span data-device="mobile">След. {!! $next_count !!}</span>
                <span data-device="desktop">
                    следующие {!! $next_count !!} @choice('front_labels.count_of_news', $next_count)
                </span>
            </li>
        @endif
    </ul>
</section>