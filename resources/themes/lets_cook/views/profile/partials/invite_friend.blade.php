@if ($invite_friend)
    <section class="profile-main__contacts profile-contacts invite-friend-block">
        <ul class="profile-contacts__list">
            <li data-contacts="name">@lang('front_labels.invite_friend_label')</li>
            <li data-contacts="birth">@lang('front_texts.invite friend description text')</li>
            <li data-contacts="name" class="code">{!! $invite_friend->code !!}</li>
        </ul>
    </section>
@endif
