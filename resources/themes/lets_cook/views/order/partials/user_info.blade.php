<section class="order__personal-data order-personal" data-steps="6">
    <h2 class="order-personal__title georgia-title">Личные данные</h2>

    <div class="order-personal__main">
        <div class="order-personal__row">
            <label for="f-personal-data-name" class="order-personal__label">Как вас зовут</label>
            @php($user_name = empty($repeat_order) ? ($user ? $user->getFullName() : '') : $repeat_order->full_name)
            <input type="text"
                   name="full_name"
                   id="f-personal-data-name"
                   class="order-personal__input input-text-small"
                   @if ($user_name) readonly @endif
                   value="{!! $user_name !!}"
                   placeholder="Имя">
        </div>

        <div class="order-personal__row">
            <label for="f-personal-data-mail" class="order-personal__label">Ваш e-mail</label>
            @php($user_email = empty($repeat_order) ? ($user ? $user->email : '') : $repeat_order->email)
            <input type="text"
                   name="email"
                   id="f-personal-data-mail"
                   class="order-personal__input input-text-small"
                   @if ($user_email) readonly @endif
                   value="{!! $user_email !!}"
                   placeholder="Электронная почта">
        </div>

        <div class="order-personal__row">
            <label for="f-personal-data-phone" class="order-personal__label">Ваш телефон</label>
            @php($user_phone = empty($repeat_order) ? ($user ? $user->phone : '+') : $repeat_order->phone)
            <input type="text"
                   name="phone"
                   id="f-personal-data-phone"
                   class="order-personal__input input-text-small"
                   @if ($user_phone) readonly @endif
                   value="{!! $user_phone !!}"
                   placeholder="Телефон">
        </div>
    </div>
</section>