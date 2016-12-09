<div class="header__top header-top header-trial-order" data-active="false">
    <a href="{!! $basket->getUrl().'?trial=true' !!}" class="header-top__link">
        Закажите пробный <span>ужин на двоих за {!! $basket->getPriceInOrder(1) !!} рублей</span>
    </a>
    <a href="#" class="header-top__close">Закрыть</a>
</div>