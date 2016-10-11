<section class="order__promocode order-promocode" @if ($user && $user->subscribe()->count()) data-fullscreen @endif>
    <div class="order-promocode__wrapper">
        <h2 class="order-promocode__title">
            <span data-device="mobile">Промокод</span>
            <span data-device="desktop">
                @lang('front_texts.promo code description on order page')
            </span>
        </h2>

        @if ($user && $user_coupons)
            <div class="order-promocode__select large-select">
                <select class="large-select__wrapper" name="coupon_id" id="order-create-coupon-id">
                    @foreach($user_coupons as $coupon)
                        @if ($coupon->available($user))
                            <option value="{!! $coupon->coupon_id !!}"
                                    @if ($coupon->default)
                                        selected
                                        @php($selected = $coupon)
                                    @endif
                                    data-code="{!! $coupon->getCode() !!}"
                                    data-main_discount="{!! $coupon->getMainDiscount() !!}"
                                    data-additional_discount="{!! $coupon->getMainDiscount() !!}"
                                    data-discount_type="{!! $coupon->getDiscountType() !!}">
                                {!! $coupon->getName() !!}
                            </option>
                        @endif
                    @endforeach
                    <option value=""
                            data-last=""
                            @if (empty($selected)) selected @endif
                            data-code=""
                            data-main_discount="0"
                            data-additional_discount="0"
                            data-discount_type="">
                        Не используется
                    </option>
                </select>
            </div>
        @endif

        <div class="order-promocode__inputs">
            <input type="text"
                   @if (!empty($selected))
                       data-main_discount="{!! $selected->getMainDiscount() !!}"
                       data-additional_discount="{!! $selected->getMainDiscount() !!}"
                       data-discount_type="{!! $selected->getDiscountType() !!}"
                       value="{!! $selected->getCode() !!}"
                   @endif
                   class="input-text-small"
                   name="coupon_code"
                   placeholder="Или введите вручную">
            <input type="button" name="order-promocode__submit" value="Пересчитать">
        </div>
    </div>
</section>