@extends('layouts.master')

@section('content')

    <main class="main profile-main">
        <h1 class="profile-main__title static-georgia-title">Личный кабинет</h1>

        <section class="profile-main__contacts profile-contacts">
            <ul class="profile-contacts__list">
                <li data-contacts="name">{!! $user->getFullName() !!}</li>
                <li data-contacts="birth">{!! get_localized_date($user->birthday, 'd-m-Y') !!}</li>
                <li data-contacts="mail">{!! $user->email !!}</li>
                <li data-contacts="phone">{!! $user->phone !!}</li>
                @if ($user->additional_phone)
                    <li data-contacts="more-phone">дополнительный номер</br>{!! $user->additional_phone !!}</li>
                @endif
                <li data-contacts="address">{!! $user->getFullAddress() !!}</li>
            </ul>
        </section>

        @include('profile.partials.coupons')
    </main>

@endsection
