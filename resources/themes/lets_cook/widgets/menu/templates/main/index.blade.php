<nav class="header-main__nav header-nav{!! $model->class ? ' '.$model->class : '' !!}" data-page="main">
    @if ($model->visible_items->count())
        <ul class="header-nav__list">
            @foreach($model->visible_items as $item)
                <li class="header-nav__item {!! $item->class !!}">
                    <a title="{!! $item->getTitle() !!}" href="{!! $item->getUrl() !!}">{!! $item->name !!}</a>
                </li>
            @endforeach
        </ul>
    @endif
</nav>