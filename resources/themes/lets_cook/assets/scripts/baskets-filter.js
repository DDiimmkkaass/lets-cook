
/* ----- BASKETS FILTER ----- */

function basketsFilter() {
    let $basketsFilter = $('.baskets-filter'),
        $panelList = $basketsFilter.find('.baskets-filter__panel-list'),
        $panelSubLists = $panelList.find('.baskets-filter__panel-subList'),
        $panelSubItems = $panelList.find('.baskets-filter__panel-subItem'),
        $basketsMain = $('.baskets-main'),
        $basketsMainItems = $basketsMain.find('.baskets-main__item');

    $panelSubLists.on('click', '.baskets-filter__panel-subItem', function() {
        let $that = $(this),
            filter = $that.attr('data-filter'),
            $matchingItems;

        $panelSubItems.removeAttr('data-active');
        $that.attr('data-active', '');

        if (filter === '0') {
            $basketsMainItems.removeAttr('data-hidden');

            basketsImagesSize();
        } else {
            $matchingItems = $basketsMainItems.filter(function() {
                return $(this).attr('data-filter') === filter;
            });

            $basketsMainItems.attr('data-hidden', '');
            $matchingItems.removeAttr('data-hidden');

            basketsImagesSize();
        }
    });

    $(window).on('resize', function() {
        if ($(window).width() < 1020) {
            $basketsMainItems.removeAttr('data-hidden');

            $panelSubItems.removeAttr('data-active');

            $panelSubItems
                .filter(function() {
                    return $(this).attr('data-filter') === '0';
                })
                .attr('data-active', '');
        }
    }).resize();
}

/* ----- end BASKETS FILTER ----- */