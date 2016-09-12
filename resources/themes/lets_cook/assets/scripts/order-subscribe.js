
/* ----- ORDER SUBSCRIBE ----- */

function orderSubscribe() {
    let $orderSubscribe = $('.order-subscribe'),
        $list = $orderSubscribe.find('.order-subscribe__list');

    $list.on('click', '.order-subscribe__item', function(e) {
        let $that = $(this),
            $radio = $that.find('input[type="radio"]');

        e.preventDefault();

        if ($radio.is(':checked')) {
            $radio.prop('checked', false);
        } else {
            $radio.prop('checked', true);
        }
    });
}

/* ----- end ORDER SUBSCRIBE ----- */