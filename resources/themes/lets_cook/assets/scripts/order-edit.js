
/* ----- ORDER EDIT ----- */

function orderEdit() {
    let $orderEdit = $('.order-edit').find('.profile-orders-content__tabs-item[data-tab="my-orders-edit"]'),
        $title = $orderEdit.find('.profile-orders-content__tabs-title[data-tab="my-orders-edit"]'),
        $orderEditWrapper = $orderEdit.find('.order-edit__wrapper'),
        $address = $orderEdit.find('.order-edit__address-wrapper'),
        $addressInner = $address.find('.order-edit__address-inner'),
        $addressTextarea = $address.find('.order-edit__address-input'),
        $addressText = $address.find('.order-edit__address-text'),
        $addressChange = $address.find('.order-edit__address-change'),
        $makeOrder = $orderEdit.find('.order-edit__make-order');


    function init() {
        $orderEdit.outerHeight($orderEditWrapper.outerHeight() + $title.outerHeight());
    }

    (function addressChange() {
        $addressChange.on('click', function() {
            if ($addressText.is('[data-active]')) {
                $addressInner.attr('data-info', 'save');
                $addressTextarea.attr('data-active', '').focus();
                $addressText.removeAttr('data-active');
                $addressChange.attr('data-info', 'save');

                init();
            } else {
                $addressInner.attr('data-info', 'change');
                $addressText.text($addressTextarea.val());
                $addressTextarea.removeAttr('data-active');
                $addressText.attr('data-active', '');
                $addressChange.attr('data-info', 'change');

                init();
            }
        });
    }());

    $makeOrder.on('click', function() {
        // ENTER YOUR CODE
    });

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end ORDER EDIT ----- */