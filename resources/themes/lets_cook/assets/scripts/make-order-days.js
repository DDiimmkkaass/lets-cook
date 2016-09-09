
/* ----- MAKE ORDER DAYS ----- */

function makeOrderDaysSize() {
    let $list = $('.order-main__list'),
        $items = $list.find('.order-main__item'),
        $itemsH3 = $items.find('h3');

    function init() {
        let titleMaxHeight = 0;

        $itemsH3.each(function() {
            let $that = $(this);

            $that.css('height', 'auto');

            if ($that.outerHeight() > titleMaxHeight) {
                titleMaxHeight = $that.outerHeight();
            }
        });

        $itemsH3.outerHeight(titleMaxHeight);
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end MAKE ORDER DAYS ----- */