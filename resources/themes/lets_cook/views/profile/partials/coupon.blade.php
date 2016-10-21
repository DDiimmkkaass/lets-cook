<tr>
    <td>{!! $coupon->getName() !!} [{!! $coupon->getAvailableCountLabel() !!}]</td>
    <td>{!! $coupon->getCode() !!}</td>
    <td>{!! $coupon->getDiscountLabel() !!}</td>
    <td>
        @if ($coupon->getExpiredAt())
            {!! get_localized_date($coupon->getExpiredAt()) !!}
        @else
            Безвременно
        @endif
    </td>
    <td>
        @if ($coupon->available($user))
            Доступен
        @else
            Истек/Использован
        @endif
    </td>
    <td class="h-pointer make-coupon-default"
        data-coupon_id="{!! $coupon->coupon_id !!}"
        data-_token="{!! csrf_token() !!}"
        data-action="{!! localize_route('coupons.make_default') !!}"
        @if ($coupon->default) data-chosen @endif>
        @if ($coupon->default)
            Выбран
        @else
            Не выбран
        @endif
    </td>
</tr>