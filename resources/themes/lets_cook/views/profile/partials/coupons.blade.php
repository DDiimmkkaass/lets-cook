<section class="profile-main__coupons profile-coupons">
    <table class="profile-coupons__table @if ($user_coupons->count() == 0) h-hidden @endif">
        <caption>Ваши купоны</caption>

        <thead>
        <tr>
            <th>Название</th>
            <th>Код</th>
            <th>Скидка</th>
            <th>Начало действия</th>
            <th>Дата истечения</th>
            <th>Статус</th>
            <th>Активация</th>
        </tr>
        </thead>

        <tbody>
        @foreach($user_coupons as $coupon)
            @include('profile.partials.coupon')
        @endforeach
        </tbody>
    </table>

    <form action="{!! localize_route('coupons.store') !!}" method="post" class="profile-coupons__add-new">
        {!! csrf_field() !!}
        <input type="text" class="input-yellow-small" name="add-coupons-text">
        <input type="submit" class="wide-orange-button" name="add-coupons-submit" value="Добавить купон">
    </form>
</section>