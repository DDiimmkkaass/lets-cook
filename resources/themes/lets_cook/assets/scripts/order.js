function order() {
    let $order = $('.main.order'),
        $makeOrderButton = $order.find('.order-main__make-order'),
        $orderAddressAndDate = $order.find('.order__address-and-date'),
        $selectCity = $orderAddressAndDate.find('select#f-select-city'),
        $selectCityName = $orderAddressAndDate.find('.order-addr-date__date-select[data-select="city-name"]'),
        $signIn = $orderAddressAndDate.find('.order-addr-date__signIn'),
        $signInPopUp = $('.header .header__sign-in');

    // SCROLL TO FORM
    $makeOrderButton.on('click', function (e) {
        e.preventDefault();

        $('html,body').animate({
            scrollTop: $orderAddressAndDate.offset().top
        }, 'slow');
    });

    // SIGN IN
    $signIn.on('click', function (e) {
        e.preventDefault();

        $signInPopUp.attr('data-active', '');
    });

    // SELECT CHANGE
    $selectCity.on('change', function () {
        let $lastOption = $(this).find('option:last');

        if ($lastOption.is(':selected')) {
            $selectCityName.attr('data-active', '');
        } else {
            $selectCityName.removeAttr('data-active');
            $selectCityName.find('input').val('');
        }

    });

}

function countList() {
    let $order = $('.main.order'),
        $controlsList = $order.find('.order-main__count-list');

    function findChanges($element) {
        let $mainList = $order.find('.order-main__list'),
            $mainItems = $mainList.find('.order-main__item'),
            $mainItemsItems = $('.order-ing__list li');

        $mainItems.removeAttr('data-active');

        $mainItems.find('[type="checkbox"]').prop('checked', false);

        $mainItemsItems.addClass('h-hidden');
        $mainItemsItems.find('[type="checkbox"]').each(function () {
            $(this).prop('checked', false).removeAttr('name');
        });
        $mainItemsItems.find('label').each(function () {
            $(this).text($(this).data('add'));
        });

        let data_count = parseInt($element.attr('data-count'), 10);

        switch (data_count) {
            case 1:
                $mainItems.each(function (index) {
                    if (index === 0) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            case 3:
                $mainItems.each(function (index) {
                    if (index === 0 || index === 2 || index === 4) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            case 4:
                $mainItems.each(function (index) {
                    if (index === 0 || index === 1 || index === 3 || index === 4) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            default:
                $mainItems.each(function (index) {
                    if (index < data_count) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;
        }

        makeOrderDaysSize();

        Order.calculateTotal();
    }

    (function init() {
        let $checked = $controlsList.find('input[type="radio"]:checked');

        findChanges($checked, null);
    })();

    $controlsList.on('change', 'input[type="radio"]', function () {
        findChanges($(this), null);
    });
}

function orderPopUp() {
    let $order = $('.main.order'),
        $editButton = $order.find('.order-main__edit'),
        $popUp = $order.find('.order__pop-up.order-pop-up'),
        $popUpBgLayout = $popUp.find('.order-pop-up__bg-layout'),
        $popUpCancel = $popUp.find('.order-pop-up-bottom__cancel'),
        $popUpSave = $popUp.find('.order-pop-up-bottom__save'),
        $list = $popUp.find('.order-pop-up__list'),
        $allTitles = $list.find('.order-day-item__title');

    $editButton.on('click', function () {
        $popUp.attr('data-active', '');
    });

    $popUpBgLayout.on('click', function () {
        $popUp.removeAttr('data-active');
    });

    $popUpCancel.on('click', function () {
        $popUp.removeAttr('data-active');
    });

    $popUpSave.on('click', function () {
        if (parseInt($('#total_dinners').text()) > 7) {
            popUp(lang_error, 'Вы не можете выбрать более 7 ужинов');
        } else {
            Order.updateRecipes();
            $popUp.removeAttr('data-active');
        }
    });

    $list.on('change', '.order-day-item__add-remove-checkbox', function () {
        let $that = $(this),
            $parent = $that.closest('.order-day-item'),
            $buttonsWrapper = $parent.find('.order-day-item__buttons');
        $editButton = $buttonsWrapper.find('.order-day-item__edit');

        if ($that.is(':checked')) {
            $buttonsWrapper.attr('data-active', '');
        } else {
            $buttonsWrapper.removeAttr('data-active');
            $editButton.find('span').text('1 ужин');
            $editButton.data('count', 1);
        }

        Order.calculateDinners();
    });

    $list.on('click', '.order-day-item__edit', function () {
        let $that = $(this),
            $parent = $that.closest('.order-day-item'),
            $change = $parent.find('.order-day-item__change');

        $change.attr('data-active', '');
    });

    $list.on('change', '.order-day-change__item input[type="radio"]', function () {
        let $that = $(this),
            number = parseInt($that.attr('data-number'), 10),
            title = $that.attr('data-title'),
            $parent = $that.closest('.order-day-item'),
            $change = $parent.find('.order-day-item__change'),
            $editButton = $parent.find('.order-day-item__edit');

        $editButton.find('span').text(title);
        $editButton.data('count', number);

        $change.removeAttr('data-active');

        Order.calculateDinners();
    });

    $(window).on('resize', function() {
        (function titlesHeight() {
            let maxHeight = 0;

            $allTitles.each(function() {
                let $that = $(this),
                    thatHeight = $that.outerHeight();

                if (thatHeight > maxHeight) {
                    maxHeight = thatHeight;
                }
            });

            $allTitles.height(maxHeight);
        }());
    }).resize();
}

/* ----- end ORDER ----- */