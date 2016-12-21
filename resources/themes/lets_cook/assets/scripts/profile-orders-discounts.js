
/* ----- PROFILE ORDERS DISCOUNTS ----- */

function profileOrdersDiscounts() {
    let $profileOrders = $('.main.profile-orders'),
        $discountTab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="discount"]'),
        $discountTitle = $discountTab.find('.profile-orders-content__tabs-title'),
        $discountMain = $discountTab.find('.profile-orders-content__main'),
        $discountCode = $discountTab.find('.profile-discount__code.discount-code'),
        $discountSubmit = $discountCode.find('.discount-code__copy');


    function setHeight() {
        if ($discountTab.is('[data-active]')) {
            $discountTab.outerHeight($discountTitle.outerHeight() + $discountMain.outerHeight());
        }
    }

    $discountSubmit.on('click', function() {
        new Clipboard('#js-copy-button');

        popUp(lang_copied, lang_codeCopyMessage)
    });

    $(window).on('resize', function() {
        setHeight();
    }).resize();
}

/* ----- end PROFILE ORDERS DISCOUNTS ----- */