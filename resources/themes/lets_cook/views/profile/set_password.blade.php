@extends('layouts.profile')

@section('_content')

    <section class="profile-edit__section profile-password-edit-section">
        {!! Form::open(['route' => 'profiles.update.password', 'method' => 'post', 'class' => 'profile-password-edit-form']) !!}

        <div class="profile-edit-section__title">Установка пароля</div>

        <div class="profile-edit-section__main order-personal__main">
            <div class="order-personal__row">
                <label for="f-personal-data-password" class="order-personal__label">
                    Пароль
                </label>
                <input name="password" type="password" id="f-personal-data-password"
                       class="order-personal__input input-text-small" placeholder="Пароль">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-password-confirmation" class="order-personal__label">
                    Подтверждение
                </label>
                <input name="password_confirmation" type="password" id="f-personal-data-password-confirmation"
                       class="order-personal__input input-text-small" placeholder="Подтверждение">
            </div>
        </div>

        <div class="profile-edit-section__buttons">
            <button type="submit" class="profile-password-edit-section__save">Сохранить</button>
        </div>
        {!! Form::close() !!}
    </section>

@endsection