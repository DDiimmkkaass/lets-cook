
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
        initWeek = 0,
        initBasket = 0;

    (function init() {
        setItems(initWeek, initBasket);

        chooseWeek();

        chooseBasket();

        onResize();
    }());

    function chooseWeek() {
        $recipesMenuChooseList.on('click', 'li.recipes-menu__chooseItem', function() {
            let week_num = parseInt($(this).attr('data-week'));

            setActiveWeek(week_num);

            setItems(week_num, 0);

            $allRecipesLink.attr('href', $('.baskets-menu__main-item[data-active]').find('.baskets-menu__sub-item').data('url'));
        });
    }

    function chooseBasket() {
        $basketsMenuList.on('click', '.baskets-menu__sub-item', function() {
            let $that = $(this),
                data_week = parseInt($that.attr('data-week')),
                data_basket = parseInt($that.attr('data-basket'));

            $allRecipesLink.attr('href', $that.data('url'));

            setItems(data_week, data_basket);
        });
    }

    function onResize() {
        $(window).on('resize', function() {
            setItemsTitleHeight();
            setContentListHeight();
        }).resize();
    }


    function setItems(week, basket) {
        $recipesMenuContentList.empty();

        $recipesMenuContentHiddenChildren.each(function() {
            let $that = $(this),
                that_week = parseInt($that.attr('data-week')),
                that_basket = parseInt($that.attr('data-basket'));

            if (that_week === week && that_basket === basket) {
                $recipesMenuContentList.append($that);
            }
        });

        setActiveWeek(week);

        setItemsPosition();
        setItemsTitleHeight();
        setContentListHeight();
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

    function setActiveWeek(week) {
        $recipesMenuChooseItems.removeAttr('data-active');
        $recipesMenuChooseItems.eq(parseInt(week)).attr('data-active', '');

        $basketsMenuItems.each(function() {
            let $that = $(this),
                data_week = parseInt($that.attr('data-week'));

            if (data_week === week) {
                $that.attr('data-active', '');
            } else {
                $that.removeAttr('data-active');
            }
        });

        $basketsMenuDesc.each(function() {
            let $that = $(this),
                data_week = parseInt($that.attr('data-week'));

            if (data_week === week) {
                $that.attr('data-active', '');
            } else {
                $that.removeAttr('data-active');
            }
        });

        $basketsMenuLink.each(function() {
            let $that = $(this),
                data_week = parseInt($that.attr('data-week'));

            if (data_week === week) {
                $that.attr('data-active', '');
            } else {
                $that.removeAttr('data-active');
            }
        });


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
}

/* ----- end RECIPES MENU ----- */