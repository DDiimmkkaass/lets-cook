
/* ----- CONTACTS SIMPLE ----- */

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

/* ----- end CONTACTS SIMPLE ----- */