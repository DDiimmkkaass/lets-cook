
/* ----- ORDER ADD MORE INFO ----- */

function orderAddMoreInfo() {
    let $baskets = $('.main.baskets'),
        $orderAddMore = $baskets.find('.order-add-more-info'),
        $wrappers = $orderAddMore.find('.order-add-more-info__wrapper');

    function setMobileInfoHeight() {

        $wrappers.each(function() {
            let $items = $(this).find('.order-add-more-info__item'),
                $itemsMobileInfo = $items.find('.more-item-info__info[data-device="mobile"]'),
                maxHeight = 0;

            $itemsMobileInfo.css('height', 'auto');

            $itemsMobileInfo.each(function() {
                let $that = $(this),
                    that__height = $that.outerHeight();

                if (that__height > maxHeight) {
                    maxHeight = that__height;
                }
            });

            $itemsMobileInfo.outerHeight(maxHeight);
        });


    }

    function setDesktopInfoHeight() {

        $wrappers.each(function() {
            let $items = $(this).find('.order-add-more-info__item'),
                $itemsInfo = $items.find('.more-item-info__info[data-device="desktop"]'),
                $itemsInfoTitle = $itemsInfo.find('.more-item-info__title'),
                $itemsInfoDesc = $itemsInfo.find('.more-item-info__desc'),
                titlesMaxHeight = 0, descMaxHeight = 0;

            $itemsInfoTitle.css('height', 'auto');
            $itemsInfoDesc.css('height', 'auto');

            $itemsInfoTitle.each(function() {
                let $that = $(this),
                    that__height = $that.outerHeight();

                if (that__height > titlesMaxHeight) {
                    titlesMaxHeight = that__height;
                }
            });

            $itemsInfoDesc.each(function() {
                let $that = $(this),
                    that__height = $that.outerHeight();

                if (that__height > descMaxHeight) {
                    descMaxHeight = that__height;
                }
            });

            $itemsInfoTitle.outerHeight(titlesMaxHeight);
            $itemsInfoDesc.outerHeight(descMaxHeight);
        });
    }


    $(window).on('resize', function() {
        setMobileInfoHeight();
        setDesktopInfoHeight();
    }).resize();

}


/* ----- end ORDER ADD MORE INFO ----- */