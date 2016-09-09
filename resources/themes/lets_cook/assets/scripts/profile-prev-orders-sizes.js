
/* ----- PROFILE PREV ORDERS SIZES ----- */

function profilePrevOrdersSizes() {
    let $profileOrders = $('.profile-orders'),
        $tab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="prev-orders"]'),
        $title = $tab.find('.profile-orders-content__tabs-title'),
        $prevList = $tab.find('.profile-orders-own__list'),
        $prevListItemsInfo = $tab.find('.profile-orders-own__item .own-order__info'),
        maxHeight;

    function init() {
        maxHeight = 0;

        $prevListItemsInfo.css('height', 'auto');

        $prevListItemsInfo.each(function() {
            let $that = $(this),
                thatHeight = $that.outerHeight();

            if (thatHeight > maxHeight) {
                maxHeight = thatHeight;
            }
        });

        $prevListItemsInfo.outerHeight(maxHeight);
        $tab.outerHeight($prevList.outerHeight() + $title.outerHeight());
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end PROFILE PREV ORDERS SIZES ----- */