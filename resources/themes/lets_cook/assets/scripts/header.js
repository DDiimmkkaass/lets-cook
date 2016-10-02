
/* ----- HEADER SCRIPTS ----- */

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
        $regBirthday = $signOutPopUp.find('input[name="sign-out__birthday"]'),
        $restoreButton = $signInPopUp.find('.sign-in__restore-button'),
        $restorePopUp = $header.find('.header__restore'),
        $restoreClose = $restorePopUp.find('.restore__close-layout, .restore__close');

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

    // RESTORE CLICK
    $restoreButton.on('click', function(e) {
        e.preventDefault();

        $signInPopUp.removeAttr('data-active');
        $restorePopUp.attr('data-active', '');
    });

    // CLOSE SIGN IN
    $signInClose.on('click', function() {
        $signInPopUp.removeAttr('data-active', '');
    });

    // CLOSE SIGN OUT
    $signOutClose.on('click', function() {
        $signOutPopUp.removeAttr('data-active');
    });

    // CLOSE RESTORE OUT
    $restoreClose.on('click', function() {
        $restorePopUp.removeAttr('data-active');
    });
}

/* ----- end HEADER SCRIPTS ----- */