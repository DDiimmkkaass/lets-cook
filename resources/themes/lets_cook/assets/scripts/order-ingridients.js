
/* ----- ORDER INGREDIENTS ----- */

function orderIngredients() {
    let $orderIng = $('.order-ing'),
        $title = $orderIng.find('.order-ing__title'),
        $lists = $orderIng.find('.order-ing__list-wrapper'),
        $addList = $lists.eq(2),
        $addListItems = $addList.find('.order-ing__list li .checkbox-button input[type="checkbox"]');

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

    $addListItems.on('change', function() {
        let $that = $(this),
            $label = $that.next();

        if ($that.is(':checked')) {
            $label.text($label.attr('data-add'));
            $(this).removeAttr('name');
            updateOrderTotal($(this).data('price'));
        } else {
            $label.text($label.attr('data-remove'));
            $(this).attr('name', $(this).data('name'));
            updateOrderTotal($(this).data('price'), 'sub');
        }
    });
}

/* ----- end ORDER INGRIDIENTS ----- */