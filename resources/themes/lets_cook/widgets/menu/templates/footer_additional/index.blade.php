<div class="footer__section-1 additional-info {!! $model->class !!}">
    @if ($model->visible_items->count())
        <ul class="additional-info__list">
            @foreach($model->visible_items as $item)
                <li class="additional-info__item {!! $item->class !!}">
                    <a title="{!! $item->getTitle() !!}" href="{!! $item->getUrl() !!}">
                        {!! $item->name !!}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>