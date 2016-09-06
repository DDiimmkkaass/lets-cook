<section class="articles-list__filter articles-list-filter">
    <div class="articles-list-filter__desc">На приготовление одного блюда понадобится не более 40 минут</div>

    <div class="articles-list-filter__panel">
        <div class="articles-list-filter__panel-wrapper">
            <ul class="articles-list-filter__list">
                <li class="articles-list-filter__item">
                    <ul class="articles-list-filter__subList">
                        <li class="articles-list-filter__subItem" data-cat="0" data-active>Все</li>
                    </ul>
                </li>

                <li class="articles-list-filter__item">
                    <div class="articles-list-filter__subTitle">Категории</div>

                    @if (count($tags_categories))
                        <ul class="articles-list-filter__subList">
                            @foreach($tags_categories as $category)
                                <li class="articles-list-filter__subItem" data-cat="{!! $category->id !!}">
                                    {!! $category->name !!}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</section>