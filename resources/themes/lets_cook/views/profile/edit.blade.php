@extends('layouts.profile')

@section('_content')

    <section class="profile-edit__section profile-edit-section">
        {!! Form::model($user, ['route' => 'profiles.update', 'method' => 'post', 'class' => 'profile-edit-form']) !!}

        <div class="profile-edit-section__title">Личные данные</div>

        <div class="profile-edit-section__main order-personal__main">
            <div class="order-personal__row">
                <label for="f-personal-data-name" class="order-personal__label">Как вас зовут</label>
                <input name="full_name" value="{!! $user->full_name !!}" type="text" id="f-personal-data-name"
                       class="order-personal__input input-text-small" placeholder="Имя">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-mail" class="order-personal__label">Ваш e-mail</label>
                <input name="email" value="{!! $user->email !!}" type="text" id="f-personal-data-mail"
                       class="order-personal__input input-text-small" placeholder="Электронная почта">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-phone" class="order-personal__label">Телефон</label>
                <input name="phone" value="{!! $user->phone !!}" type="text" id="f-personal-data-phone"
                       class="order-personal__input input-text-small" placeholder="Телефон">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-add-phone" class="order-personal__label">Доп. телефон</label>
                <input name="additional_phone" value="{!! $user->additional_phone !!}" type="text"
                       id="f-personal-data-add-phone"
                       class="order-personal__input input-text-small" placeholder="Доп. телефон">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-add-sex-man" class="order-personal__label">Ваш пол</label>

                <div class="order-personal__input">
                    @foreach($genders as $gender => $label)
                        <div class="order-personal__radio-item profile-radio">
                            <input type="radio"
                                   id="f-personal-data-add-sex-{!! $gender !!}"
                                   name="gender"
                                   @if ($user->gender == $gender) checked @endif
                                   value="{!! $gender !!}">
                            <label for="f-personal-data-add-sex-{!! $gender !!}">{!! $label !!}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-add-birthday" class="order-personal__label">Дата рождения</label>
                <input name="birthday"
                       value="{!! $user->birthday ? $user->birthday : '' !!}"
                       type="text"
                       id="f-personal-data-add-birthday"
                       class="order-personal__input input-text-small" placeholder="Дата рождения">
            </div>
        </div>

        <div class="profile-edit-section__title">Детали доставки</div>

        <div class="profile-edit-section__main order-personal__main">
            <div class="order-personal__row">
                <label for="f-personal-data-add-city" class="order-personal__label">Город</label>

                <div class="order-personal__input">
                    <div class="order-personal__select main-select">
                        <select class="main-select__wrapper" name="city_id" id="f-personal-data-add-city" required>
                            <option value="" disabled selected hidden>Выберите город</option>
                            @foreach($cities as $city)
                                <option value="{!! $city->id !!}"
                                        @if ($user->city_id == $city->id)
                                            selected
                                            @php($_selected = true)
                                        @endif>
                                    {!! $city->name !!}
                                </option>
                            @endforeach
                            <option value="" @if (!$user->city_id) selected @endif>Другой..</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="order-personal__row" data-row="another-city">
                <label for="f-personal-data-add-another-city" class="order-personal__label">Другой город</label>

                <input name="city_name" value="{!! $user->city_name !!}" type="text"
                       id="f-personal-data-add-another-city"
                       class="order-personal__input input-text-small" placeholder="Ваш город">
            </div>

            <div class="order-personal__row">
                <label for="f-personal-data-add-address" class="order-personal__label">Адрес</label>

                <input name="address" value="{!! $user->address !!}" type="text" id="f-personal-data-add-address"
                       class="order-personal__input input-text-small" placeholder="Ваш адрес">
            </div>

            <div class="order-personal__row" data-row="comment">
                <label for="f-personal-data-add-more" class="order-personal__label">Дополнительно</label>

                <textarea name="comment" id="f-personal-data-add-more" cols="30" rows="3"
                          class="order-personal__input textarea-small"
                          placeholder="Еще полезная для нас информация">{!! $user->comment !!}</textarea>
            </div>
        </div>

        <div class="profile-edit-section__buttons">
            <a href="#" class="profile-edit-section__save">Редактировать профиль</a>
            <a href="{!! localize_route('profiles.index') !!}" class="profile-edit-section__cancel">Отмена</a>
        </div>
        {!! Form::close() !!}
    </section>

@endsection