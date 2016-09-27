@extends('layouts.profile')

@section('_content')

    <section class="profile-main__contacts profile-contacts">
        <ul class="profile-contacts__list">
            <li data-contacts="name">{!! $user->getFullName() !!}</li>
            @if ($user->birthday)
                <li data-contacts="birth">{!! get_localized_date($user->birthday, 'd-m-Y') !!}</li>
            @endif
            <li data-contacts="mail">{!! $user->email !!}</li>
            <li data-contacts="phone">{!! $user->phone !!}</li>
            @if ($user->additional_phone)
                <li data-contacts="more-phone">дополнительный номер</br>{!! $user->additional_phone !!}</li>
            @endif
            <li data-contacts="address">{!! $user->getFullAddress() !!}</li>
        </ul>

        <div class="profile-contacts__buttons">
            <a href="{!! localize_route('profiles.edit') !!}" class="profile-contacts__buttons-link black-long-button">Редактировать профиль</a>
            <a href="#" class="profile-contacts__buttons-link black-long-button">Банковские карточки</a>
        </div>
    </section>

    @include('profile.partials.coupons')

@endsection
