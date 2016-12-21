@if ($invite_friend)
    <section class="profile-main__contacts profile-contacts invite-friend-block">
        <ul class="profile-contacts__list">
            <li data-contacts="name"></li>
            <li data-contacts="birth"></li>
            <li data-contacts="name" class="code"></li>
        </ul>
    </section>
@endif

@if ($invite_friend)
    <div class="profile-discount__wrapper">
        <div class="profile-discount__info discount-info">
            <div class="discount-info__desc">
                @lang('front_texts.invite friend description text')
            </div>

            <ul class="discount-info__list">
                @if (variable('invite_friend_compensation') && variable('invite_friend_compensation_type'))
                    <li class="discount-info__item discount-item" data-sign="{!! variable('invite_friend_compensation_type') !!}">
                        <div class="discount-item__value">
                            {!! variable('invite_friend_compensation') !!}<span>{!! trans('front_labels.discount_short_label_'.variable('invite_friend_compensation_type')) !!}</span>
                        </div>
                        <div class="discount-item__title">
                            @lang('front_labels.invite_friend_your_discount_label')
                        </div>
                    </li>
                @endif
                <li class="discount-info__item discount-item" data-sign="{!! $invite_friend->getStringDiscountType() !!}">
                    <div class="discount-item__value">
                        {!! $invite_friend->discount !!}<span>{!! trans('front_labels.discount_short_label_'.$invite_friend->getStringDiscountType()) !!}</span>
                    </div>
                    <div class="discount-item__title">@lang('front_labels.invite_friend_friend_discount_label')</div>
                </li>
            </ul>
        </div>

        <div class="profile-discount__code discount-code">
            <div id="js-copy-code" class="discount-code__value">{!! $invite_friend->code !!}</div>
            <div id="js-copy-button"
                 data-clipboard-target="#js-copy-code"
                 class="discount-code__copy">Скопировать</div>
        </div>
    </div>
@endif