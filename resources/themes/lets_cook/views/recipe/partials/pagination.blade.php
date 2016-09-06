<section class="articles-list__pagination articles-list-pag">
    <div class="articles-list-pag__all" data-active>Все</div>

    <ul class="articles-list-pag__list">
        <li class="articles-list-pag__item" data-pagination="prev">
            <span data-device="mobile">Пред. 10</span>
            <span data-device="desktop">предыдущие 10 рецептов</span>
        </li>

        <li class="articles-list-pag__item" data-pagination="next" data-active>
            <span data-device="mobile">След. {!! $next_count !!}</span>
            <span data-device="desktop">следующие {!! $next_count !!} рецептов</span>
        </li>
    </ul>
</section>