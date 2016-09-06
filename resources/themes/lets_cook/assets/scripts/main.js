'use strict';

/* ----- GLOBAL VARIABLES ----- */



/* ----- end GLOBAL VARIABLES ----- */


/* ----- FUNCTION DECLARATION ----- */

function validateEmail(email) {
    let re = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;

    return re.test(email);
}

function popUp(title = '', content = '', callback = function() {}) {
    let $popUp = $('.pop-up'),
        $title = $popUp.find('.pop-up__title'),
        $content = $popUp.find('.pop-up__content');

    $title.text(title);
    $content.html(content);
    $popUp.attr('data-active', '');

    $popUp.off().on('click', '.pop-up__close-layout, .pop-up__close', function() {
        $popUp.removeAttr('data-active');

        setTimeout(function () {
            $title.empty();
            $content.empty();
        }, 1000);
    });

    callback();
}

function datePicker($obj) {
    let picker = new Pikaday({
        field: document.getElementById($obj.attr('id')),
        firstDay: 1,
        format: 'D MMMM YYYY',
        i18n: {
            previousMonth : 'Предыдущий месяц',
            nextMonth     : 'Следующий месяц',
            months        : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            weekdays      : ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
            weekdaysShort : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
        }
    });
}

function textAreaAutoGrow($element) {
    $element.outerHeight($element.scrollHeight);
}

function headerActions() {
    let $header = $('.header'),
        $headerTop = $header.find('.header-top'),
        $closeButton = $headerTop.find('.header-top__close'),
        $profileButton = $header.find('.menu-mobile .menu-mobile__item[data-item="profile"], .menu-desktop .menu-desktop__item[data-item="profile"]'),
        $signInPopUp = $header.find('.header__sign-in'),
        $signInClose = $signInPopUp.find('.sign-in__close-layout, .sign-in__close'),
        $regButton = $signInPopUp.find('.sign-in__reg-button'),
        $signOutPopUp = $header.find('.header__sign-out'),
        $signOutClose = $signOutPopUp.find('.sign-out__close-layout, .sign-out__close'),
        $regBirthday = $signOutPopUp.find('input[name="sign-out__birthday"]');

    // REGISTRATION BIRTHDAY DATEPICKER
    datePicker($regBirthday);

    // HEADER CLOSE BUTTON CLICK
    $closeButton.on('click', function() {
        let $headerTop = $(this).parent();

        $headerTop.attr('data-active', 'false');
    });

    // HEADER PROFILE CLICK
    $profileButton.on('click', function(e) {
        e.preventDefault();

        $signInPopUp.attr('data-active', '');
    });

    // REGISTRATION CLICK
    $regButton.on('click', function(e) {
        e.preventDefault();

        $signInPopUp.removeAttr('data-active');
        $signOutPopUp.attr('data-active', '');
    });

    // CLOSE SIGN IN
    $signInClose.on('click', function() {
        $signInPopUp.removeAttr('data-active', '');
    });

    // CLOSE SIGN OUT
    $signOutClose.on('click', function() {
        $signOutPopUp.removeAttr('data-active');
    });


}

function aboutServiceVideo() {
    let $aboutService = $('.about-service'),
        $title = $aboutService.find('.about-service__title'),
        $content = $aboutService.find('.about-service__content'),
        $videoSection = $aboutService.find('.about-service__video'),
        $iframeSection = $videoSection.find('iframe'),
        $closeButton = $aboutService.find('.about-service__close');

    // VIDEO RATIO
    $iframeSection
        .attr('aspectRatio', $iframeSection.height() / $iframeSection.width())
        .removeAttr('height')
        .removeAttr('width');

    $(window).resize(function() {
        let newWidth = $videoSection.width();

        $iframeSection
            .width(newWidth)
            .height(newWidth * $iframeSection.attr('aspectRatio'));
    }).resize();

    // ABOUT SERVICE VIDEO
    $('.about-service__title').on('click', function() {
        $aboutService.attr('data-active', 'true');
        $content.attr('data-active', 'true');
        $title.attr('data-active', 'false');
    });

    $closeButton.on('click', function() {
        $iframeSection.each(function(){
            this.contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*')
        });

        $aboutService.removeAttr('data-active');
        $content.removeAttr('data-active');
        $title.removeAttr('data-active');
    });
}

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

    const imagesRatio = 140 / 300;

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
            currentWidth = $allMenusItems.eq(0).width(),
            currentHeight = Math.round(currentWidth * imagesRatio),
            titlesHeight = 0;

        $allTitles.each(function() {
            let $that = $(this);

            $that.css('height', 'auto');

            if ($that.outerHeight() > titlesHeight || $that.css('height') === 'auto') {
                titlesHeight = $that.outerHeight();
            }
        });

        $allTitles.height(titlesHeight);

        $allMenusItems.find('.recipes-menu__img').height(currentHeight);
        $recipesMenuContentList.height($allMenusItems.eq(0).height() * 3);
    }).resize();
}

function specReview() {
    let $specReview = $('.spec-review'),
        $specText = $specReview.find('.spec-review__text'),
        $specAllParagraphs = $specText.children(),
        $specShow = $specReview.find('.spec-review__show'),
        $specHide = $specReview.find('.spec-review__hide');

    (function init() {
        $specText.height($specAllParagraphs.first().height());
    }());

    $specShow.on('click', function() {
        let tempHeight = 0;

        $specAllParagraphs.each(function() {
            tempHeight += $(this).outerHeight(true);
        });

        $specText.outerHeight(tempHeight);

        $specShow.attr('data-active', 'false');
        $specHide.attr('data-active', 'true');
    });

    $specHide.on('click', function() {

        $specText.height($specAllParagraphs.first().height());

        $specShow.removeAttr('data-active');
        $specHide.removeAttr('data-active');
    });
}

function subscribeNews() {
    let $form = $('.subscribe-form'),
        $text = $form.find('input[name="subscribe-mail"]');

    // RETURN DEFAULT VALUE FOR DESCRIPTION
    $text.on('focus', function() {
        $(this).removeAttr('data-error');
    });

    $form.on('submit', function(e) {
        e.preventDefault();

        if (validateEmail($text.val())) {
            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                dataType: 'json',
                data: {
                    'email': $text.val(),
                    '_token': $form.find('[name=\'_token\']').val()
                },

                success: function(data) {
                    let clearInput = function() {
                        $text.val('');
                    };

                    switch (data.status) {
                        case 'success':

                            popUp('Подписка на новости', data.message, clearInput);

                            break;

                        case 'error':
                            popUp('Подписка на новости', data.message, clearInput);

                            $text.attr('data-error', '');

                            break;
                    }

                    console.log('subscribe ajax success');
                },

                error: function(response) {
                    let tempMessage = '';

                    $.each(response.responseJSON, function(i, item) {
                        tempMessage += item + '\n';
                    });

                    popUp('Подписка на новости', tempMessage);

                    $text.attr('data-error', '');

                    console.log('subscribe ajax error');
                }
            });
        } else {
            popUp('Подписка на новости', 'Неправильный E-mail. Попытайтесь еще раз.');

            $text.attr('data-error', '');
        }
    });
}

function makeOrderDaySize() {
    let $list = $('.order-main__list'),
        $items = $list.find('.order-main__item'),
        $itemsH3 = $items.find('h3');

    const imagesRatio = 160 / 240;

    function init() {
        let titleMaxHeight = 0;

        $items.each(function() {
            let $that = $(this),
                $image = $that.find('.order-main__img'),
                $title = $that.find('h3');

            $image.height(Math.round($that.width() * imagesRatio));

            $title.css('height', 'auto');

            if ($title.outerHeight() > titleMaxHeight) {
                titleMaxHeight = $title.outerHeight();
            }
        });

        $itemsH3.outerHeight(titleMaxHeight);
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

function orderIngridients() {
    let $orderIng = $('.order-ing'),
        $title = $orderIng.find('.order-ing__title'),
        $lists = $orderIng.find('.order-ing__list-wrapper'),
        $addList = $lists.eq(2),
        $addListItems = $addList.find('.order-ing__list li .checkbox-button input[name="order-ingridients"]');

    $title.on('click', function(e) {
        let orderHeight = parseInt($title.css('line-height'), 10),
            allListsHeight = 0;

        e.preventDefault();

        if ($orderIng.attr('data-active') === 'true') {
            $orderIng.removeAttr('style');

            $orderIng.removeAttr('data-active');

            $title.removeAttr('data-active');
        } else {
            $lists.each(function () {
                allListsHeight += $(this).outerHeight(true);
            });

            $orderIng.css('max-height', allListsHeight + orderHeight);

            $orderIng.attr('data-active', 'true');

            $title.attr('data-active', '');
        }
    });


    $addListItems.on('change', function() {
        let $that = $(this),
            $label = $that.next(),
            myCheckboxes = {};

        if ($that.is(':checked')) {
            $label.text($label.attr('data-remove'));
        } else {
            $label.text($label.attr('data-add'));
        }

        $addListItems.each(function() {
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


                console.log('ORDER INGIRIDIENTS CHECKBOX AJAX SUCCESS');
            },

            error: function() {

                console.log('ORDER INGIRIDIENTS CHECKBOX AJAX ERROR');
            }
        });
    });
}

function orderAndMoreSize() {
    let $orderAddMore = $('.order-add-more'),
        $list = $orderAddMore.find('.order-add-more__list-wrapper'),
        $listItems = $list.find('.order-add-more__item.more-item'),
        $listItemsCheckboxes = $listItems.find('.checkbox-button input[name="order-add-more"]');

    const imageRatio = 120 / 180;

    function init() {
        $listItemsCheckboxes.each(function() {
            let $that = $(this),
                $parent = $that.closest('.more-item');

            if ($that.is(':checked')) {
                $parent.attr('data-active', '');
            }
        });

        $listItems.each(function() {
            let $that = $(this),
                $image = $that.find('.more-item__img');

            $image.outerHeight($image.outerWidth() * imageRatio);
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

function articlesList($rootElement, loadedPage) {
    let $articles = $rootElement,
        $searchForm = $articles.find('.articles-list-search__form'),
        $filterList = $articles.find('.articles-list-filter__list'),
        $articlesPanel = $articles.find('.articles-list-filter__panel'),
        $articlesList = $articles.find('.articles-list-main__list'),
        $articlesLoader = $articles.find('.articles-list-main__loader'),
        $pagination = $articles.find('.articles-list__pagination'),
        $paginationAll = $pagination.find('.articles-list-pag__all'),
        $paginationPrev = $pagination.find('.articles-list-pag__item[data-pagination="prev"]'),
        $paginationNext = $pagination.find('.articles-list-pag__item[data-pagination="next"]'),
        articlesObj = {},
        page = loadedPage,
        currCat = 0,
        currTag = 0,
        currPage = 1,
        textSearch = '';

    // FUNCTIONS
    function articlesAjax(cat = 0, tag = 0, pageNum = 0, searchText = '') {
        let ajaxUrl = page + '/' + cat + '/' + tag + '?page=' + pageNum + '&search_text=' + searchText;

        console.log(ajaxUrl);

        return $.ajax({
            type: 'GET',
            url: ajaxUrl,
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },

            beforeSend: function() {
                $articlesLoader.attr('data-active', '');
            },

            success: function(data) {
                if (data.status === 'error') {
                    let errorMessage = data.message;


                }

                console.log('articles ajax success');
            },

            error: function() {

                console.log('articles ajax error');
            }
        });
    }

    function articlesOutput() {
        $articlesList.empty();

        $.each(articlesObj[page], function(index, item) {
            let currentArticle = '<li class="articles-list-main__item article-item">'
                + '<div class="article-item__main">'
                + '<a href="' + item.href + '" class="article-item__img" style="background-image: url(' + '\'' + item.image + '\'' + ');"></a>'
                + '<div class="article-item__content">'
                + '<a href="' + item.href + '" class="article-item__title">' + item.name + '</a>'
                + '<ul class="article-item__tag-list">';

            $.each(item.tags, function(index, item) {
                currentArticle += '<li class="article-item__tag-item" data-tag="' + item.id + '">' + item.name + '</li>';
            });

            currentArticle += '</ul>'
                + '<div class="article-item__description"><span>Ингридиенты: </span>' + item.description + '</div>'
                + '</div>'
                + '</div>';

            if (page === 'recipes') {
                currentArticle += '<div class="article-item__additional">'
                    + '<div class="article-item__rating">'
                    + '<div class="article-item__rating-title">рейтинг:</div>'
                    + '<div class="article-item__rating-stars">';

                switch (item.rating) {
                    case 1:
                        currentArticle += '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 2:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 3:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 4:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>';

                        break;

                    case 5:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<<span data-active></span>';

                        break;

                    default:
                        currentArticle += '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;
                }

                currentArticle +='</div>'
                    + '</div>'
                    + '<div class="article-item__time">'
                    + '<div class="article-item__time-title">время:</div>'
                    + '<div class="article-item__time-value">' + item.cooking_time + ' минут</div>'
                    + '</div>'
                    + '</div>'
                    + '</li>';
            }

            $articlesList.append($(currentArticle));
        });

        $paginationNext.text(articlesObj.next_count_label);
    }

    $filterList.on('click', '.articles-list-filter__subItem', function() {
       let $that = $(this);

        currCat = $that.attr('data-cat');

        $.when(articlesAjax(currCat, currTag = 0, currPage = 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                articlesOutput();

                $searchForm.find('input[name="search-text"]').val('');

                $filterList.find('.articles-list-filter__subItem').removeAttr('data-active');
                $that.attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationAll.removeAttr('data-active');
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationAll.attr('data-active', '');
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationAll.on('click', function() {
        $.when(articlesAjax(currCat, currTag, currPage = 0, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'fast');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                $paginationAll.removeAttr('data-active');
                $paginationPrev.removeAttr('data-active');
                $paginationNext.removeAttr('data-active');
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationPrev.on('click', function() {
        $.when(articlesAjax(currCat, currTag, currPage -= 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'slow');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationNext.on('click', function() {
        $.when(articlesAjax(currCat, currTag, currPage += 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'slow');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $articlesList.on('click', '.article-item__tag-item', function() {
        let $that = $(this);

        currTag = $that.attr('data-tag');

        $.when(articlesAjax(currCat, currTag, currPage = 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'fast');

                articlesOutput();

                currPage = 1;

                $articlesList.find('.article-item__tag-item').removeAttr('data-active');
                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationAll.removeAttr('data-active');
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationAll.attr('data-active', '');
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $searchForm.on('submit', function(e) {
        e.preventDefault();

        let $input = $(this).find('input[name="search-text"]');

        if ($input.val()) {
            $.when(articlesAjax(currCat = 0, currTag = 0, currPage = 1, textSearch = $input.val()))
                .done(function(ra) {
                    articlesObj = $.extend(true, {}, ra);

                    articlesOutput();

                    $filterList.find('.articles-list-filter__subItem').removeAttr('data-active');
                    $filterList.find('.articles-list-filter__subItem[data-cat="0"]').attr('data-active', '');

                    if ((currPage - 1) < 1) {
                        $paginationPrev.removeAttr('data-active');
                    } else {
                        $paginationPrev.attr('data-active', '');
                    }

                    if (articlesObj.next_count < 1) {
                        $paginationAll.removeAttr('data-active');
                        $paginationNext.removeAttr('data-active');
                    } else {
                        $paginationAll.attr('data-active', '');
                        $paginationNext.attr('data-active', '');
                    }
                })
                .always(function() {
                    setTimeout(function() {
                        $articlesLoader.removeAttr('data-active');
                    }, 300);
                });
        } else {
            $input.focus();
        }
    });
}

function profileTabs() {
    let $profileOrders = $('.profile-orders'),
        $tabList = $profileOrders.find('.profile-orders-content__tabs-list'),
        $tabItems = $tabList.find('.profile-orders-content__tabs-item'),
        $prevOrders = $profileOrders.find('.profile-orders-content__prev-orders');

    $tabList.on('click', '.profile-orders-content__tabs-title', function() {
        let $that = $(this),
            $parent = $that.closest('.profile-orders-content__tabs-item'),
            $main = $parent.find('.profile-orders-content__main');

        $tabItems.css('height', 'auto');
        $tabItems.removeAttr('data-active');
        $parent.attr('data-active', '');

        if ($that.is('[data-tab="my-orders"]') || $that.is('[data-tab="prev-orders"]')) {

            console.dir($tabItems);

            profileOrdersSizes();
            profilePrevOrdersSizes();

            $prevOrders.removeAttr('data-active');

        } else if ($that.is('[data-tab="my-orders-edit"]')) {

            orderEdit();

            $prevOrders.attr('data-active', 'false');

        } else {

            $parent.outerHeight($that.outerHeight() + $main.outerHeight());

            $prevOrders.attr('data-active', 'false');

        }
    });
}

function profileOrders() {
    let $profileOrders = $('.profile-orders'),
        $mobile = $profileOrders.find('.profile-orders-user__mobile'),
        $desktop = $profileOrders.find('.profile-orders-user__desktop')

    $mobile.on('click', function() {
        if ($desktop.is('[data-active]')) {
            $desktop.removeAttr('data-active');
        } else {
            $desktop.attr('data-active', '');
        }
    });
}

function profileSubscribe() {
    let $profileSubscribe = $('.profile-orders-content__tabs-item[data-tab="subscribe"]'),
        $title = $profileSubscribe.find('.profile-orders-content__tabs-title'),
        $main = $profileSubscribe.find('.profile-orders-content__main.profile-subscribe'),
        $more = $profileSubscribe.find('.profile-subscribe__add'),
        $moreButton = $more.find('.profile-subscribe__add-button'),
        $closeButton = $more.find('.profile-subscribe__add-close');

    $moreButton.on('click', function() {
        if ($more.is('[data-active]')) {
            $more.removeAttr('data-active');
        } else {
            $more.attr('data-active', '');
        }
    });

    $closeButton.on('click', function() {
        $more.removeAttr('data-active');
    });

    $(window).on('resize', function() {
        $profileSubscribe.css('height', 'auto');

        if ($profileSubscribe.is('[data-active]')) {
            $profileSubscribe.outerHeight($title.outerHeight() + $main.outerHeight());
        }
    });
}

function profileOrdersSizes() {
    let $profileOrders = $('.profile-orders'),
        $tab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="my-orders"]'),
        $title = $tab.find('.profile-orders-content__tabs-title'),
        $ownList = $tab.find('.profile-orders-own__list'),
        $ownListItemsInfo = $tab.find('.profile-orders-own__item .own-order__info'),
        maxHeight;

    function init() {
        maxHeight = 0;

        $ownListItemsInfo.css('height', 'auto');

        $ownListItemsInfo.each(function() {
            let $that = $(this),
                thatHeight = $that.outerHeight();

            if (thatHeight > maxHeight) {
                maxHeight = thatHeight;
            }
        });

        $ownListItemsInfo.outerHeight(maxHeight);
        $tab.outerHeight($ownList.outerHeight() + $title.outerHeight());
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

function profilePrevOrdersSizes() {
    let $profileOrders = $('.profile-orders'),
        $tab = $profileOrders.find('.profile-orders-content__tabs-item[data-tab="prev-orders"]'),
        $title = $tab.find('.profile-orders-content__tabs-title'),
        $prevList = $tab.find('.profile-orders-own__list'),
        $prevListItemsInfo = $tab.find('.profile-orders-own__item .own-order__info'),
        maxHeight;

    function init() {
        maxHeight = 0;

        $prevListItemsInfo.css('height', 'auto');

        $prevListItemsInfo.each(function() {
            let $that = $(this),
                thatHeight = $that.outerHeight();

            if (thatHeight > maxHeight) {
                maxHeight = thatHeight;
            }
        });

        $prevListItemsInfo.outerHeight(maxHeight);
        $tab.outerHeight($prevList.outerHeight() + $title.outerHeight());
    }

    $(window).on('resize', function() {
        init();
    }).resize();
}

function orderEdit() {
    let $orderEdit = $('.order-edit').find('.profile-orders-content__tabs-item[data-tab="my-orders-edit"]'),
        $title = $orderEdit.find('.profile-orders-content__tabs-title[data-tab="my-orders-edit"]'),
        $orderEditWrapper = $orderEdit.find('.order-edit__wrapper'),
        $address = $orderEdit.find('.order-edit__address-wrapper'),
        $addressInner = $address.find('.order-edit__address-inner'),
        $addressTextarea = $address.find('.order-edit__address-input'),
        $addressText = $address.find('.order-edit__address-text'),
        $addressChange = $address.find('.order-edit__address-change'),
        $makeOrder = $orderEdit.find('.order-edit__make-order');


    function init() {
        $orderEdit.outerHeight($orderEditWrapper.outerHeight() + $title.outerHeight());
    }

    (function addressChange() {
        $addressChange.on('click', function() {
            if ($addressText.is('[data-active]')) {
                $addressInner.attr('data-info', 'save');
                $addressTextarea.attr('data-active', '').focus();
                $addressText.removeAttr('data-active');
                $addressChange.attr('data-info', 'save');

                init();
            } else {
                $addressInner.attr('data-info', 'change');
                $addressText.text($addressTextarea.val());
                $addressTextarea.removeAttr('data-active');
                $addressText.attr('data-active', '');
                $addressChange.attr('data-info', 'change');

                init();
            }
        });
    }());

    $makeOrder.on('click', function() {
        // ENTER YOUR CODE
    });

    $(window).on('resize', function() {
        init();
    }).resize();
}

function contactsSimple() {
    let $map = $('#js-contacts-map');

    $(window).on('resize', function () {
        if ($(window).width() > 760) {
            $map.outerHeight($map.outerWidth() * (406 / 1200));
        } else {
            $map.outerHeight(280);
        }
    }).resize();
}

/* ----- end FUNCTION DECLARATION ----- */


/* ----- DOCUMENT READY ----- */

$(function() {

    /* ----- INIT PAGE ----- */

    headerActions();

    if ($('main[class="main"]').length) {
        aboutServiceVideo();

        recipesMenu();

        subscribeNews();
    }

    if ($('main[class="main order"]').length) {
        orderIngridients();
    }

    if ($('main[class="main baskets"]').length) {
        basketsFilter();
    }

    if ($('main[class="main articles-list"]').length) {
        let $articles = $('.main.articles-list'),
            loadedPage = 'articles';

        articlesList($articles, loadedPage);
    }

    if ($('main[class="main blog-list articles-list"]').length) {
        let $articles = $('.main.blog-list.articles-list'),
            loadedPage = 'blog';

        articlesList($articles, loadedPage);
    }


    if ($('main[class="main recipes-list articles-list"]').length) {
        let $recipes = $('.main.recipes-list.articles-list'),
            loadedPage = 'recipes';

        articlesList($recipes, loadedPage);
    }


    if ($('main[class="main recipe-simple"]').length) {
        orderIngridients();
        subscribeNews();
    }

    if ($('main[class="main profile-orders"]').length) {
        profileOrders();
    }

    if ($('main[class="main profile-orders"]').length) {
        profileTabs();
        profileOrdersSizes();
        profilePrevOrdersSizes();
        profileSubscribe();
    }

    if ($('main[class="main profile-orders order-edit"]').length) {
        profileTabs();
        orderEdit();
        profileSubscribe();
    }

    if ($('main[class="main contacts-simple"]').length) {
        contactsSimple();
    }

    /* ----- end INIT PAGE ----- */


    /* ----- CLICK ACTIONS ----- */


    /* ----- end CLICK ACTIONS ----- */


    /* ----- PLUGINS HANDLING ----- */


    /* ----- end PLUGINS HANDLING ----- */
});

/* ----- end DOCUMENT READY ----- */


/* ----- WINDOW READY ----- */

$(window).load(function() {

    if ($('main[class="main"]').length) {
        specReview();
    }

    if ($('main[class="main order"]').length) {
        makeOrderDaySize();

        orderAndMoreSize();
    }

    if ($('main[class="main baskets"]').length) {
        basketsImagesSize();
    }
});

/* ----- end WINDOW READY ----- */