
/* ----- PROFILE ORDERS SIZES ----- */

function profileOrdersSizes() {
    let $profileOrders = $('.profile-orders'),
        $tab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="my-orders"]'),
        $title = $tab.find('.profile-orders-content__tabs-title'),
        $ownList = $tab.find('.profile-orders-own__list'),
        $ownListItems = $ownList.find('.profile-orders-own__item'),
        $ownListItemsInfo = $ownListItems.find('.own-order__info'),
        maxHeight;

    function init() {
        setItemsInfoHeight();
        setItemsHeight();
    }

    function setItemsHeight() {
        maxHeight = 0;

        $ownListItems.css('height', 'auto');

        $ownListItems.each(function() {
            let $that = $(this),
                thatHeight = $that.outerHeight();

            if (thatHeight > maxHeight) {
                maxHeight = thatHeight;
            }
        });

        $ownListItems.outerHeight(maxHeight);
        $tab.outerHeight($ownList.outerHeight() + $title.outerHeight());
    }

    function setItemsInfoHeight() {
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
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end PROFILE ORDERS SIZES ----- */