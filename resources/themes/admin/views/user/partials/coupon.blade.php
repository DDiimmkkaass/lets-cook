<tr>
    <td>{!! $coupon->getName() !!} [{!! $coupon->getAvailableCountLabel() !!}]</td>
    <td>{!! $coupon->getCode() !!}</td>
    <td>{!! $coupon->getDiscountLabel() !!}</td>
    <td>
        @if ($coupon->getStartedAt())
            {!! get_localized_date($coupon->getStartedAt()) !!}
        @else
            @lang('labels.untimely')
        @endif
    </td>
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
            @lang('labels.not_available')
        @endif
    </td>
    <td>
        <div class="make-coupon-default @if ($coupon->default) active @endif"
            data-user_id="{!! $coupon->user_id !!}"
            data-coupon_id="{!! $coupon->coupon_id !!}"
            data-token="{!! csrf_token() !!}">
            @if ($coupon->default)
                @lang('labels.cancel')
            @else
                @lang('labels.activate')
            @endif
        </div>
    </td>
</tr>