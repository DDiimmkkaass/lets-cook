
/* ----- ORDER ADD MORE ----- */

function orderAddMore() {
    let $orderAddMore = $('.order-add-more'),
        $list = $orderAddMore.find('.order-add-more__list'),
        $listItems = $list.find('.order-add-more__item.more-item'),
        $listItemsCheckboxes = $listItems.find('.checkbox-button input[name="order-add-more"]');

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

    $list.on('click', '.order-add-more__item', function() {
        let $that = $(this),
            $checkbox = $that.find('input[name="order-add-more"]'),
            $label = $checkbox.next(),
            myCheckboxes = {};

        if ($checkbox.is(':checked')) {
            $checkbox.prop('checked', false);
            $label.text($label.attr('data-add'));
            $that.removeAttr('data-active');
        } else {
            $checkbox.prop('checked', true);
            $label.text($label.attr('data-remove'));
            $that.attr('data-active', '');
        }

        $listItemsCheckboxes.each(function() {
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


                console.log('ORDER ADD MORE CHECKBOX AJAX SUCCESS');
            },

            error: function() {

                console.log('ORDER ADD MORE CHECKBOX AJAX ERROR');
            }
        });
    });
}

/* ----- end ORDER ADD MORE ----- */