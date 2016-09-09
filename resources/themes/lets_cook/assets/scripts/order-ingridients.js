
/* ----- ORDER INGRIDIENTS ----- */

function orderIngridients() {
    let $orderIng = $('.order-ing'),
        $title = $orderIng.find('.order-ing__title'),
        $lists = $orderIng.find('.order-ing__list-wrapper'),
        $addList = $lists.eq(2),
        $addListItems = $addList.find('.order-ing__list li .checkbox-button input[name="order-ingridients"]');

    // SHOW-HIDE BLOCK in MOBILE VERSION
    $title.on('click', function(e) {
        let orderHeight = parseInt($title.css('line-height'), 10),
            allListsHeight = 0;

        e.preventDefault();

        if ($orderIng.attr('data-active') === 'true') {
            $orderIng.removeAttr('style');

            $orderIng.removeAttr('data-active');

            $title.removeAttr('data-active');
        } else {
            $lists.each(function () {
                allListsHeight += $(this).outerHeight(true);
            });

            $orderIng.css('max-height', allListsHeight + orderHeight);

            $orderIng.attr('data-active', 'true');

            $title.attr('data-active', '');
        }
    });

    // CHECKBOX CHANGE EVENT
    $addListItems.on('change', function() {
        let $that = $(this),
            $label = $that.next(),
            myCheckboxes = {};

        if ($that.is(':checked')) {
            $label.text($label.attr('data-remove'));
        } else {
            $label.text($label.attr('data-add'));
        }

        $addListItems.each(function() {
            let $that = $(this),
                thatObj = { [$that.attr('data-id')]: $that.is(':checked')};

            Object.assign(myCheckboxes, thatObj);
        });

        $.ajax({
            type: 'POST',
            url: 'order.php',
            dataType: 'json',
            cache: false,
            data: JSON.stringify(myCheckboxes), // {"1":false,"2":true,"3":false}
            xhrFields: {
                withCredentials: true
            },

            success: function() {


                console.log('ORDER INGIRIDIENTS CHECKBOX AJAX SUCCESS');
            },

            error: function() {

                console.log('ORDER INGIRIDIENTS CHECKBOX AJAX ERROR');
            }
        });
    });
}

/* ----- end ORDER INGRIDIENTS ----- */