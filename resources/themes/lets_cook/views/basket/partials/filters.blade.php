<section class="baskets-top__filter baskets-filter">
    <div class="baskets-filter__desc">На приготовление одного блюда понадобится не более 40 минут</div>

    <div class="baskets-filter__panel">
        <ul class="baskets-filter__panel-list">
            <li class="baskets-filter__panel-item">
                <ul class="baskets-filter__panel-subList">
                    <li class="baskets-filter__panel-subItem" data-filter="0" data-active>Все</li>
                </ul>
            </li>

            <li class="baskets-filter__panel-item">
                <h2 class="baskets-filter__panel-title">Для двоих</h2>
                <ul class="baskets-filter__panel-subList">
                    @for ($i = 5; $i >= 3; $i--)
                        <li class="baskets-filter__panel-subItem" data-filter="2_{!! $i !!}">
                            {!! $i !!} @choice('front_labels.count_of_dinners', $i)
                        </li>
                    @endfor
                </ul>
            </li>

            <li class="baskets-filter__panel-item">
                <h2 class="baskets-filter__panel-title">Для четверых</h2>
                <ul class="baskets-filter__panel-subList">
                    @for ($i = 5; $i >= 3; $i--)
                        <li class="baskets-filter__panel-subItem" data-filter="4_{!! $i !!}">
                            {!! $i !!} @choice('front_labels.count_of_dinners', $i)
                        </li>
                    @endfor
                </ul>
            </li>
        </ul>
    </div>
</section>