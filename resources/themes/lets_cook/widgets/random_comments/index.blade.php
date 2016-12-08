<section class="clients-reviews">
    <h2 class="clients-reviews__title">@lang('front_labels.users_comments')</h2>

    <h3 class="clients-reviews__subtitle clients-reviews__add-new">@lang('front_labels.add_comment')</h3>

    @if ($list->count())
        <ul class="clients-reviews__list">
            @foreach($list as $item)
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img"
                         @if (!empty($item->getUserImage()))
                         style="background-image: url({!! thumb($item->getUserImage(), 104) !!});"
                            @endif>
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">{!! $item->getUserName() !!}</div>
                        <div class="review-item__comment">{!! $item->comment !!}</div>
                        <div class="review-item__date">{!! $item->getDate() !!}</div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="clients-reviews__bottom">
        <a href="{!! localize_route('comments.index') !!}"
           title="@lang('front_labels.all_comments')"
           class="green-long-button">
            @lang('front_labels.all_comments')
        </a>
    </div>

    @include('widgets.random_comments.partials.form')
</section>

