
/* ----- ORDER ADD MORE INFO ----- */

function orderAddMoreInfo() {
    let $baskets = $('.main.baskets'),
        $orderAddMore = $baskets.find('.order-add-more-info'),
        $list = $orderAddMore.find('.order-add-more-info__list'),
        $items = $list.children(),
        $itemsMobileInfo = $items.find('.more-item-info__info[data-device="mobile"]'),
        $itemsDesktopInfo = $items.find('.more-item-info__info[data-device="desktop"]');


    function setMobileInfoHeight() {
        let maxHeight = 0;

        $itemsMobileInfo.css('height', 'auto');

        $itemsMobileInfo.each(function() {
            let $that = $(this),
                that__height = $that.outerHeight();

            if (that__height > maxHeight) {
                maxHeight = that__height;
            }
        });

        $itemsMobileInfo.outerHeight(maxHeight);
    }

    function setDesktopInfoHeight() {
        let maxHeight = 0;

        $itemsDesktopInfo.css('height', 'auto');

        $itemsDesktopInfo.each(function() {
            let $that = $(this),
                that__height = $that.outerHeight();

            if (that__height > maxHeight) {
                maxHeight = that__height;
            }
        });

        $itemsDesktopInfo.outerHeight(maxHeight);
    }


    $(window).on('resize', function() {
        setMobileInfoHeight();
        setDesktopInfoHeight();

        console.log('fldsjf');
    }).resize();

}


/* ----- end ORDER ADD MORE INFO ----- */