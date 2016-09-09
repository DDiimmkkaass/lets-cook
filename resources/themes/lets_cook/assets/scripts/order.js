
/* ----- ORDER ----- */

function order() {
    let $order = $('.main.order'),
        $makeOrderButton = $order.find('.order-main__make-order'),
        $orderAddressAndDate = $order.find('.order__address-and-date');

    // SCROLL TO FORM
    $makeOrderButton.on('click', function(e) {
        e.preventDefault();

        $('html,body').animate({
            scrollTop: $orderAddressAndDate.offset().top
        }, 'slow');
    });
}

/* ----- end ORDER ----- */