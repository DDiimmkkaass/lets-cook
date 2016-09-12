
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
            updateOrderTotal($checkbox.data('price'), 'sub');
            $label.text($label.attr('data-add'));
            $that.removeAttr('data-active');
        } else {
            $checkbox.prop('checked', true);
            $checkbox.attr('name', $checkbox.data('name'));
            updateOrderTotal($checkbox.data('price'));
            $label.text($label.attr('data-remove'));
            $that.attr('data-active', '');
        }
    });
}

/* ----- end ORDER ADD MORE ----- */