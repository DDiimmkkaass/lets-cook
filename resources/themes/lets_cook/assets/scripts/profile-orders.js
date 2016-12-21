
/* ----- PROFILE ORDERS ----- */

function profileOrders() {
    let $profileOrders = $('.profile-orders, .profile-main'),
        $mobile = $profileOrders.find('.profile-orders-user__mobile'),
        $desktop = $profileOrders.find('.profile-orders-user__desktop');

    $mobile.on('click', function() {
        if ($desktop.is('[data-active]')) {
            $desktop.removeAttr('data-active');
        } else {
            $desktop.attr('data-active', '');
        }
    });
}

/* ----- end PROFILE ORDERS ----- */