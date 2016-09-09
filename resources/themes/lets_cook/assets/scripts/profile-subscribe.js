
/* ----- PROFILE SUBSCRIBE ----- */

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

/* ----- end PROFILE SUBSCRIBE ----- */