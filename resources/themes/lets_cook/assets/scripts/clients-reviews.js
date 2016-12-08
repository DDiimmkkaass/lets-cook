
/* ----- CLIENTS REVIEWS ----- */

function clientsReviews() {
    let $clientsReviews = $('.clients-reviews'),
        $addNewButton = $clientsReviews.find('.clients-reviews__add-new'),
        $popUp = $clientsReviews.find('.clients-reviews__add-review'),
        $popUpForm = $popUp.find('.add-review__form'),
        $popUpText = $popUp.find('.add-review__text'),
        $popUpClose = $clientsReviews.find('.add-review__close-layout, .add-review__close'),
        $header = $('.header'),
        $profileButton = $header.find('.menu-mobile .menu-mobile__item[data-item="profile"], .menu-desktop .menu-desktop__item[data-item="profile"]');

    $addNewButton.on('click', function() {
        let authorized = parseInt($popUpForm.data('authorized'));

        if (authorized) {
            $popUp.attr('data-active', '');
        } else {
            popUp(lang_error, lang_errorUnauthorized, null, function () {
                $profileButton.click();
            });
        }
    });

    $popUpClose.on('click', function() {
        $popUp.removeAttr('data-active');
        $popUpText.val('');
    });

    $popUpForm.on('submit', function(e) {
        e.preventDefault();

        let $_form = $(this);
        let data = Form.getFormData($_form);

        console.log(data);

        $.ajax({
            type: 'POST',
            url: $_form.attr('action'),
            data: data,
            error: function (response) {
                Form.processFormSubmitError(response, $_form);
            },
            success: function (response) {
                if (response.status == 'success') {
                    popUp(lang_success, response.message);
                    $popUpClose.click();
                } else {
                    popUp(lang_error, response.message);
                }
            }
        })

    });
}

/* ----- end CLIENTS REVIEWS ----- */