
/* ----- ORDER EDIT ----- */

function orderEdit() {
    let $orderEdit = $('.order-edit').find('.profile-orders-content__tabs-item[data-tab="my-orders-edit"]'),
        $title = $orderEdit.find('.profile-orders-content__tabs-title[data-tab="my-orders-edit"]'),
        $orderEditWrapper = $orderEdit.find('.order-edit__wrapper'),
        $address = $orderEdit.find('.order-edit__address-wrapper'),
        $addressInner = $address.find('.order-edit__address-inner'),
        $addressTextarea = $address.find('.order-edit__address-input'),
        $addressCityId = $address.find('#order-edit-city-id'),
        $addressCityName = $address.find('#order-edit-city-name'),
        $addressAddress = $address.find('#order-edit-address'),
        $addressText = $address.find('.order-edit__address-text'),
        $addressChange = $address.find('.order-edit__address-change'),
        $makeOrder = $orderEdit.find('.order-edit__make-order'),
        $orderAddItems = $orderEdit.find('.order-edit__add-list .order-edit__add-item ');

    (function addressChange() {
        $addressCityId.on('change', function () {
            if (parseInt($(this).val()) == 0) {
                $addressCityName.removeClass('h-hidden')
            } else {
                $addressCityName.addClass('h-hidden').val('')
            }
        });

        $addressChange.on('click', function() {
            if ($addressText.is('[data-active]')) {
                $addressInner.attr('data-info', 'save');
                $addressTextarea.attr('data-active', '').focus();
                $addressText.removeAttr('data-active');
                $addressChange.attr('data-info', 'save');

                init();
            } else {
                let city_id = $addressCityId.find('option:selected').text(),
                    city_name = $addressCityName.val(),
                    address = $addressAddress.val();

                address = 'г. ' + (city_name == '' ? city_id : city_name) + ' ' + address;

                $addressInner.attr('data-info', 'change');
                $addressText.text(address);
                $addressTextarea.removeAttr('data-active');
                $addressText.attr('data-active', '');
                $addressChange.attr('data-info', 'change');

                init();
            }
        });
    }());

    function orderAddItemsSetTitleHeight() {
        let maxHeight = 0,
            $titles = $orderAddItems.find('.order-add-item__title');

        $titles.css('height', 'auto');

        $titles.each(function() {
            let that_height = $(this).height();

            if (that_height > maxHeight) {
                maxHeight = that_height;
            }
        });

        $titles.height(maxHeight);
    }

    function init() {
        $orderEdit.outerHeight($orderEditWrapper.outerHeight() + $title.outerHeight());

        orderAddItemsSetTitleHeight();
    }

    $makeOrder.on('click', function() {
        // ENTER YOUR CODE
    });

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end ORDER EDIT ----- */