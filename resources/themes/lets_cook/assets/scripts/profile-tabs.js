
/* ----- PROFILE TABS ----- */

function profileTabs() {
    let $profileOrders = $('.profile-orders'),
        $tabList = $profileOrders.find('.profile-orders-content__tabs-list'),
        $tabItems = $tabList.find('.profile-orders-content__tabs-item'),
        $prevOrders = $profileOrders.find('.profile-orders-content__prev-orders');

    $tabList.on('click', '.profile-orders-content__tabs-title', function() {
        let $that = $(this),
            $parent = $that.closest('.profile-orders-content__tabs-item'),
            $main = $parent.find('.profile-orders-content__main');

        $tabItems.css('height', 'auto');
        $tabItems.removeAttr('data-active');
        $parent.attr('data-active', '');

        if ($that.is('[data-tab="my-orders"]') || $that.is('[data-tab="prev-orders"]')) {

            console.dir($tabItems);

            profileOrdersSizes();
            profilePrevOrdersSizes();

            $prevOrders.removeAttr('data-active');

        } else if ($that.is('[data-tab="my-orders-edit"]')) {

            orderEdit();

            $prevOrders.attr('data-active', 'false');

        } else {
            if ($(this).is('[data-tab="subscribe"]')) {
                BasketSubscribe.initPage();
            }

            $parent.outerHeight($that.outerHeight() + $main.outerHeight());

            $prevOrders.attr('data-active', 'false');

        }
    });
}

/* ----- end PROFILE TABS ----- */