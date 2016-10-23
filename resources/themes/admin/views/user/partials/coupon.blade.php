<tr>
    <td>{!! $coupon->getName() !!} [{!! $coupon->getAvailableCountLabel() !!}]</td>
    <td>{!! $coupon->getCode() !!}</td>
    <td>{!! $coupon->getDiscountLabel() !!}</td>
    <td>
        @if ($coupon->getExpiredAt())
            {!! get_localized_date($coupon->getExpiredAt()) !!}
        @else
            @lang('labels.untimely')
        @endif
    </td>
    <td>
        @if ($coupon->available($user))
            @lang('labels.available')
        @else
            @lang('labels.expired')/@lang('labels.used')
        @endif
    </td>
    <td>
        @if ($coupon->default)
            @lang('labels.default_coupon')
        @endif
    </td>
</tr>