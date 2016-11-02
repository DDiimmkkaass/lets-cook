<div class="profile-edit-section__title">Данные карты</div>

<div class="profile-edit-section__main order-personal__main h-card-form">
    <div class="order-personal__row">
        <label for="f-personal-data-name" class="order-personal__label">Название</label>
        <input name="name" value="{!! old('name') ?: $card->name !!}" type="text" id="f-personal-data-name"
               class="order-personal__input input-text-small" placeholder="название">
    </div>

    <div class="order-personal__row">
        <label for="f-personal-data-number" class="order-personal__label h-number-label">
            Последние 4 цифры номера карты
        </label>
        <input name="number" value="{!! old('number') ?: $card->number !!}" type="text" id="f-personal-data-number"
               class="order-personal__input input-text-small" placeholder="номер">
    </div>

    <div class="order-personal__row">
        <label for="f-personal-data-add-default" class="order-personal__label">По умолчанию</label>

        <div class="order-personal__input">
            <div class="order-personal__radio-item profile-radio">
                <input type="radio"
                       id="f-personal-data-default-1"
                       name="default"
                       @if ($card->default) checked @endif
                       value="1">
                <label for="f-personal-data-default-1">Да</label>
            </div>
            <div class="order-personal__radio-item profile-radio">
                <input type="radio"
                       id="f-personal-data-default-0"
                       name="default"
                       @if (!$card->default) checked @endif
                       value="0">
                <label for="f-personal-data-default-0">Нет</label>
            </div>
        </div>
    </div>
</div>

<div class="profile-edit-section__buttons">
    <button type="submit"
            class="h-card-form-section__save black-long-button {!! isset($button_type) ? $button_type: '' !!}">
        Сохранить
    </button>
    <a href="{!! localize_route('profiles.cards.index') !!}" class="h-card-form-section__cancel black-long-button">Отмена</a>
</div>