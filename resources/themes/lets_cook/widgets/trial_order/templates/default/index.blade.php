<div class="header__top header-top" data-active="true">
    <a href="{!! localize_route('order.index', ['basket_id' => $basket->id, 'trial' => true]) !!}" class="header-top__link">
        Закажите пробный <span>ужин на двоих за {!! $basket->getPriceInOrder(1) !!} рублей</span>
    </a>
    <a href="#" class="header-top__close">Закрыть</a>
</div>