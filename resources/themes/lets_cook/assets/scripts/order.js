function order() {
    let $order = $('.main.order'),
        $makeOrderButton = $order.find('.order-main__make-order'),
        $orderAddressAndDate = $order.find('.order__address-and-date'),
        $selectCity = $orderAddressAndDate.find('select#f-select-city'),
        $selectCityName = $orderAddressAndDate.find('.order-addr-date__date-select[data-select="city-name"]');

    // SCROLL TO FORM
    $makeOrderButton.on('click', function(e) {
        e.preventDefault();

        $('html,body').animate({
            scrollTop: $orderAddressAndDate.offset().top
        }, 'slow');
    });

    // SELECT CHANGE
    $selectCity.on('change', function() {
        let $lastOption = $(this).find('option:last');

        if ($lastOption.is(':selected')) {
            $selectCityName.attr('data-active', '');
        } else {
            $selectCityName.removeAttr('data-active');
            $selectCityName.find('input').val('');
        }

    });
}

/* ----- end ORDER ----- */