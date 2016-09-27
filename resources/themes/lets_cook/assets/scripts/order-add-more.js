
/* ----- ORDER ADD MORE ----- */

function orderAddMore() {
    let $orderAddMore = $('.order-add-more'),
        $list = $orderAddMore.find('.order-add-more__list'),
        $listItems = $list.find('.order-add-more__item.more-item'),
        $listItemsCheckboxes = $listItems.find('.checkbox-button input[type="checkbox"]');

    function init() {
        $listItemsCheckboxes.each(function() {
            let $that = $(this),
                $parent = $that.closest('.more-item');

            if ($that.is(':checked')) {
                $parent.attr('data-active', '');
            }
        });
    }

    $(window).on('resize', function() {
        init();
    }).resize();

    $list.on('click', '.order-add-more__item', function(e) {
        e.preventDefault();

        let $that = $(this),
            $checkbox = $that.find('input[type="checkbox"]'),
            $label = $checkbox.next();

        if ($checkbox.is(':checked')) {
            $checkbox.prop('checked', false);
            $checkbox.removeAttr('name');
            $label.text($label.attr('data-add'));
            $that.removeAttr('data-active');
            //remove
            $('.additional-basket-item-' + $checkbox.data('id')).remove()
        } else {
            $checkbox.prop('checked', true);
            $checkbox.attr('name', $checkbox.data('name'));
            $label.text($label.attr('data-remove'));
            $that.attr('data-active', '');
            //add
            let html = '<li class="order-submit__item additional-basket-item-' + $checkbox.data('id') + '">' +
                '<div class="order-submit__subTitle">' + $checkbox.data('basket_name') + '</div></li>';
            $('.order-submit__list').append(html);
        }

        Order.calculateTotal();
    });
}

/* ----- end ORDER ADD MORE ----- */