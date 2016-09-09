
/* ----- RECIPES MENU ----- */

function recipesMenu() {
    let $recipesMenu = $('.recipes-menu'),
        $recipesMenuChooseList = $recipesMenu.find('.recipes-menu__choose'),
        $recipesMenuChooseItems = $recipesMenuChooseList.children(),
        $recipesMenuContentList = $recipesMenu.find('.recipes-menu__content'),
        $recipesMenuContentItems = $recipesMenuContentList.children(),
        $thisWeekList = $recipesMenuContentItems.eq(0).find('.recipes-menu__list'),
        $nextWeekList = $recipesMenuContentItems.eq(1).find('.recipes-menu__list'),
        $thisWeekItems = $thisWeekList.find('.recipes-menu__item'),
        $nextWeekItems = $nextWeekList.find('.recipes-menu__item'),
        $thisWeekAll = $recipesMenuContentItems.eq(0).find('.recipes-menu__all'),
        $nextWeekAll = $recipesMenuContentItems.eq(1).find('.recipes-menu__all'),
        $allMenusItems = $recipesMenu.find('.recipes-menu__item'),
        activeMenuNum = 0;

    (function init() {
        $recipesMenuChooseItems.eq(0).attr('data-active', '');
        $recipesMenuContentItems.eq(0).attr('data-active', '');

        switch ($thisWeekItems.length) {
            case 1:
                $thisWeekAll.attr('data-position', '1');

                break;

            case 2:
                $thisWeekAll.attr('data-position', '2');

                break;

            case 3:
                $thisWeekAll.attr('data-position', '3');
                $thisWeekList.attr('data-direction', 'column');

                break;

            case 4:
                $thisWeekAll.attr('data-position', '4');

                break;

            case 5:
                $thisWeekAll.attr('data-position', '5');

                break;

            case 6:
                $thisWeekAll.attr('data-position', '6');

                break;
        }

        switch ($nextWeekItems.length) {
            case 1:
                $nextWeekAll.attr('data-position', '1');

                break;

            case 2:
                $nextWeekAll.attr('data-position', '2');

                break;

            case 3:
                $nextWeekAll.attr('data-position', '3');
                $nextWeekList.attr('data-direction', 'column');

                break;

            case 4:
                $nextWeekAll.attr('data-position', '4');

                break;

            case 5:
                $nextWeekAll.attr('data-position', '5');

                break;

            case 6:
                $nextWeekAll.attr('data-position', '6');

                break;
        }
    }());

    // RECIPES MENU CHOOSE CLICK
    $recipesMenuChooseList.on('click', 'li', function() {
        let selfNumber = $(this).index();

        if (selfNumber !== activeMenuNum) {
            $recipesMenuChooseItems.eq(selfNumber).attr('data-active', '');
            $recipesMenuContentItems.eq(selfNumber).attr('data-active', '');

            $recipesMenuChooseItems.eq(activeMenuNum).removeAttr('data-active');
            $recipesMenuContentItems.eq(activeMenuNum).removeAttr('data-active');

            activeMenuNum = selfNumber;
        }

    });

    $(window).on('resize', function() {
        let $allTitles = $allMenusItems.find('.recipes-menu__title'),
            titlesHeight = 0;

        $allTitles.each(function() {
            let $that = $(this);

            $that.css('height', 'auto');

            if ($that.outerHeight() > titlesHeight || $that.css('height') === 'auto') {
                titlesHeight = $that.outerHeight();
            }
        });

        $allTitles.height(titlesHeight);

        $recipesMenuContentList.height($allMenusItems.eq(0).height() * 3);
    }).resize();
}

/* ----- end RECIPES MENU ----- */