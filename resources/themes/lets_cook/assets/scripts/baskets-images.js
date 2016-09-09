
/* ----- BAKSETS IMAGES ----- */

function basketsImagesSize() {
    let $basketsMain = $('.baskets-main'),
        $basketsItems = $basketsMain.find('.baskets-main__item'),
        $basketsMainImg = $basketsItems.find('.baskets-main-item__first .baskets-main-item__img'),
        $basketsMainTitle = $basketsItems.find('.baskets-main-item__item .baskets-main-item__item-title');

    const mainImageRatio = 200 / 360;

    function init() {
        let maxHeightTitle = 0;

        $basketsMainImg.each(function() {
            let $that = $(this);

            if ($(window).width() < 1020) {
                $that.height($that.width() * mainImageRatio);
            } else {
                $that.height(335);
            }
        });

        $basketsMainTitle.each(function() {
            let $that = $(this);

            $that.css('height', 'auto');

            if ($that.outerHeight() > maxHeightTitle) {
                maxHeightTitle = $that.outerHeight();
            }
        });

        $basketsMainTitle.outerHeight(maxHeightTitle);
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

/* ----- end BAKSETS IMAGES ----- */