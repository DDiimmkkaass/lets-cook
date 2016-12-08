'use strict';

/* ----- GLOBAL VARIABLES ----- */



/* ----- end GLOBAL VARIABLES ----- */

/* ----- FUNCTION DECLARATION ----- */



/* ----- end FUNCTION DECLARATION ----- */


/* ----- DOCUMENT READY ----- */

$(function() {

    /* ----- INIT PAGE ----- */

    headerActions();

    if ($('main[class="main"]').length) {
        aboutServiceVideo();

        recipesMenu();

        subscribeNews();

        clientsReviews();
    }

    if ($('main[class="main order"]').length) {
        order();
        orderIngredients();
        orderAddMore();
        orderSubscribe();
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

    if ($('main[class="main comments-list articles-list"]').length) {
        let $articles = $('.main.comments-list.articles-list'),
            loadedPage = 'comments';

        articlesList($articles, loadedPage);
    }


    if ($('main[class="main recipe-simple"]').length) {
        orderIngredients();
        subscribeNews();
    }

    if ($('main[class="main profile-main"]').length) {
        profileOrders();
    }

    if ($('main[class="main profile-edit"]').length) {
        profileEdit();
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
        makeOrderDaysSize();

        countList();

        orderPopUp();

        orderSteps();
    }

    if ($('main[class="main baskets"]').length) {
        basketsImagesSize();

        orderAddMoreInfo();
    }
});

/* ----- end WINDOW READY ----- */