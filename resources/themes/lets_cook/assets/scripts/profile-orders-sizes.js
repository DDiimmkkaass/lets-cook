
/* ----- PROFILE ORDERS SIZES ----- */

function profileOrdersSizes() {
    let $profileOrders = $('.profile-orders'),
        $tab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="my-orders"]'),
        $title = $tab.find('.profile-orders-content__tabs-title'),
        $ownList = $tab.find('.profile-orders-own__list'),
        $ownListItemsInfo = $tab.find('.profile-orders-own__item .own-order__info'),
        maxHeight;

    function init() {
        maxHeight = 0;

        $ownListItemsInfo.css('height', 'auto');

        $ownListItemsInfo.each(function() {
            let $that = $(this),
                thatHeight = $that.outerHeight();

            if (thatHeight > maxHeight) {
                maxHeight = thatHeight;
            }
        });

        $ownListItemsInfo.outerHeight(maxHeight);
        $tab.outerHeight($ownList.outerHeight() + $title.outerHeight());
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end PROFILE ORDERS SIZES ----- */