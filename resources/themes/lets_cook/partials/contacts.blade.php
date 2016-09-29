<div class="header-main__menu header-menu">
    <ul class="header-menu__mobile menu-mobile">
        <li class="menu-mobile__item" data-item="basket">
            <a href="{!! localize_route('baskets.index', 'current') !!}"></a>
        </li>
        <li class="menu-mobile__item" data-item="phone">
            <a href="tel:{!! preg_replace('/\D/', '', variable('moscow_phone')) !!}"></a>
        </li>

        <li class="menu-mobile__item" @if (!$user) data-item="profile" @else data-item="profile-index" @endif>
            <a href="{!! localize_route('profiles.index') !!}"></a>
        </li>
    </ul>

    <ul class="header-menu__desktop menu-desktop">
        <li class="menu-desktop__item" data-item="phone">
            <a href="tel:{!! preg_replace('/\D/', '', variable('moscow_phone')) !!}">
                Москва {!! variable('moscow_phone') !!}
            </a>
        </li>
        @if (!$user)
            <li class="menu-desktop__item" data-item="profile">
                <a href="{!! localize_route('profiles.index') !!}">Личный кабинет</a>
            </li>
        @else
            <li class="menu-desktop__item" data-item="profile-index">
                <a href="{!! localize_route('profiles.index') !!}">Здраствуйте, {!! $user->getFullName() !!}</a>
            </li>
        @endif
    </ul>
</div>