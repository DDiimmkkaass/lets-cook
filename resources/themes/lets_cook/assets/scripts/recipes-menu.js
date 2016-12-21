
/* ----- RECIPES MENU ----- */

function recipesMenu() {
    let $recipesMenu = $('.recipes-menu'),
        $basketsMenu = $('.baskets-menu'),
        $recipesMenuChooseList = $recipesMenu.find('.recipes-menu__choose'),
        $recipesMenuChooseItems = $recipesMenuChooseList.children(),
        $recipesMenuContentHidden = $recipesMenu.find('.recipes-menu__hidden'),
        $recipesMenuContentHiddenChildren = $recipesMenuContentHidden.children(),
        $recipesMenuContent = $recipesMenu.find('.recipes-menu__content'),
        $recipesMenuContentList = $recipesMenu.find('.recipes-menu__list'),
        $recipesMenuContentAllButton = $recipesMenu.find('.recipes-menu__all'),
        $basketsMenuList = $basketsMenu.find('.baskets-menu__main-list'),
        $basketsMenuItems = $basketsMenuList.find('.baskets-menu__main-item'),
        $basketsMenuDesc = $basketsMenu.find('.baskets-menu__desc'),
        $basketsMenuLink = $basketsMenu.find('.baskets-menu__details'),
        $allRecipesLink = $recipesMenu.find('.recipes-menu__all'),
        $_allRecipesLink = $basketsMenu.find('.baskets-menu__to-order'),
        currentBasket = 0,
        currentWeek = 0,
        currentCurrentWeek = 0,
        currentNextWeek = 0;

    (function init() {
        chooseBasket();

        chooseWeek();

        firstInit();

        onResize();
    }());

    function firstInit() {
        let $firstBasket = $basketsMenuItems.eq(0);

        $firstBasket.trigger('click');
    }

    function chooseBasket() {
        $basketsMenuList.on('click', '.baskets-menu__main-item', function() {
            let $that               = $(this),
                data_basket         = parseInt($that.attr('data-basket')),
                data_current_week   = $that.attr('data-current-week'), // "true"/"false"
                data_next_week      = $that.attr('data-next-week'), // "true"/"false"
                active_week         = null,
                url                 = null;

            if (data_current_week === 'true') {
                active_week = 0;
                url = $that.data('current-week-url');
            } else {
                active_week = 1;
                url = $that.data('next-week-url');
            }
            
            $basketsMenuItems.attr('data-active', 'false');
            $that.attr('data-active', 'true');

            $allRecipesLink.attr('href', url);
            $_allRecipesLink.attr('href', url);

            $basketsMenuList.find('.baskets-menu__sub-item').removeAttr('data-active-item');
            $that.attr('data-active-item', '');

            let delivery_dates = $that.data('delivery_dates'),
                $showed_delivery_dates = $that.closest('.baskets-menu').find('.delivery-dates[data-week="0"]');

            console.log(delivery_dates)
            console.log($showed_delivery_dates)

            if (delivery_dates) {
                $showed_delivery_dates.text(delivery_dates);
            } else {
                $showed_delivery_dates.text($showed_delivery_dates.data('delivery_dates'));
            }

            $basketsMenuDesc.removeAttr('data-active').filter('[data-week="' + active_week + '"]').attr('data-active', '');
            $basketsMenuLink.removeAttr('data-active').filter('[data-week="' + active_week + '"]').attr('data-active', '');

            setItems(data_basket, data_current_week, data_next_week, active_week);
            setWeeks(data_current_week, data_next_week);
        });
    }

    function setItems(basket, currWeek, nextWeek, activeWeek) {
        $recipesMenuContentList.empty();

        $recipesMenuContentHiddenChildren.each(function() {
            let $that = $(this),
                that_week = parseInt($that.attr('data-week')),
                that_basket = parseInt($that.attr('data-basket'));

            if (that_week === activeWeek && that_basket === basket) {
                $recipesMenuContentList.append($that);
            }
        });

        currentBasket       = basket;
        currentWeek         = activeWeek;
        currentCurrentWeek  = currWeek;
        currentNextWeek     = nextWeek;

        setItemsPosition();
        setItemsTitleHeight();
        setContentListHeight();
    }

    function setWeeks(curr_week, next_week) {
        $recipesMenuChooseItems.attr('data-show', 'false');

        if (curr_week === 'true') {
            $recipesMenuChooseItems.eq(0).attr('data-show', 'true');
        }

        if (next_week === 'true') {
            $recipesMenuChooseItems.eq(1).attr('data-show', 'true');
        }

        $recipesMenuChooseItems.removeAttr('data-active');
        $recipesMenuChooseList
            .find('.recipes-menu__chooseItem[data-show="true"]')
            .eq(0)
            .attr('data-active', '');
    }

    function chooseWeek() {
        $recipesMenuChooseList.on('click', 'li.recipes-menu__chooseItem', function() {
            let week_num = parseInt($(this).attr('data-week')),
                $activeBasket = $basketsMenuItems.filter('[data-active="true"]'),
                url = null;

            $recipesMenuChooseItems.removeAttr('data-active');
            $recipesMenuChooseItems.eq(week_num).attr('data-active', '');

            if (week_num == 0) {
                url = $activeBasket.data('current-week-url');
            } else {
                url = $activeBasket.data('next-week-url');
            }

            if (url) {
                $allRecipesLink.attr('href', url);
                $_allRecipesLink.attr('href', url);
            }

            $basketsMenuDesc.removeAttr('data-active').filter('[data-week="' + week_num + '"]').attr('data-active', '');
            $basketsMenuLink.removeAttr('data-active').filter('[data-week="' + week_num + '"]').attr('data-active', '');

            setItems(currentBasket, currentCurrentWeek, currentNextWeek, week_num);
        });
    }

    function onResize() {
        $(window).on('resize', function() {
            setItemsTitleHeight();
            setContentListHeight();
        }).resize();
    }


    function setItemsPosition() {
        let $recipesMenuContentItems = $recipesMenuContentList.children();

        switch ($recipesMenuContentItems.length) {
            case 1:
                $recipesMenuContentAllButton.attr('data-position', '1');
                $recipesMenuContentList.attr('data-direction', 'row');

                break;

            case 2:
                $recipesMenuContentAllButton.attr('data-position', '2');
                $recipesMenuContentList.attr('data-direction', 'row');

                break;

            case 3:
                $recipesMenuContentAllButton.attr('data-position', '3');
                $recipesMenuContentList.attr('data-direction', 'column');

                break;

            case 4:
                $recipesMenuContentAllButton.attr('data-position', '4');
                $recipesMenuContentList.attr('data-direction', 'row');

                break;

            case 5:
                $recipesMenuContentAllButton.attr('data-position', '5');
                $recipesMenuContentList.attr('data-direction', 'row');

                break;

            case 6:
                $recipesMenuContentAllButton.attr('data-position', '6');
                $recipesMenuContentList.attr('data-direction', 'row');

                break;
        }
    }

    function setItemsTitleHeight() {
        let $allTitles = $recipesMenuContentList.children().find('.recipes-menu__title'),
            titlesHeight = 0;

        $allTitles.each(function() {
            let $that = $(this);

            $that.css('height', 'auto');

            if ($that.outerHeight() > titlesHeight || $that.css('height') === 'auto') {
                titlesHeight = $that.outerHeight();
            }
        });

        $allTitles.css('height', titlesHeight);
    }

    function setContentListHeight() {
        $recipesMenuContent.height($recipesMenuContentList.children().eq(0).height() * 3);
    }

    $(document).on("ready", function () {
        $('.go-to-choose-basket').on('click', function (e) {
            e.preventDefault();

            $('html,body').animate({
                scrollTop: $recipesMenu.offset().top
            }, 'slow');
        });
    });
}

/* ----- end RECIPES MENU ----- */