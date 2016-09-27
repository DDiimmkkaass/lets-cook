function order() {
    let $order = $('.main.order'),
        $makeOrderButton = $order.find('.order-main__make-order'),
        $orderAddressAndDate = $order.find('.order__address-and-date'),
        $selectCity = $orderAddressAndDate.find('select#f-select-city'),
        $selectCityName = $orderAddressAndDate.find('.order-addr-date__date-select[data-select="city-name"]'),
        $signIn = $orderAddressAndDate.find('.order-addr-date__signIn'),
        $signInPopUp = $('.header .header__sign-in');

    // SCROLL TO FORM
    $makeOrderButton.on('click', function(e) {
        e.preventDefault();

        $('html,body').animate({
            scrollTop: $orderAddressAndDate.offset().top
        }, 'slow');
    });

    // SIGN IN
    $signIn.on('click', function(e) {
        e.preventDefault();

        $signInPopUp.attr('data-active', '');
    });

    // SELECT CHANGE
    $selectCity.on('change', function() {
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
        $controlsList = $order.find('.order-main__count-list'),
        $mainList = $order.find('.order-main__list'),
        $mainItems = $mainList.find('.order-main__item'),
        $mainItemsItems = $('.order-ing__list li');

    function findChanges($element) {
        let data_count = parseInt($element.attr('data-count'), 10);

        $mainItems.removeAttr('data-active');

        $mainItems.find('[type="checkbox"]').prop('checked', false);

        $mainItemsItems.addClass('h-hidden');
        $mainItemsItems.find('[type="checkbox"]').each(function () {
            $(this).prop('checked', false).removeAttr('name');
        });
        $mainItemsItems.find('label').each(function () {
            $(this).text($(this).data('add'));
        });

        switch (data_count) {
            case 1:
                $mainItems.each(function(index) {
                    if (index === 0) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            case 3:
                $mainItems.each(function(index) {
                    if (index === 0 || index === 2 || index === 4) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            case 4:
                $mainItems.each(function(index) {
                    if (index === 0 || index === 1 || index === 3 || index === 4) {
                        $(this).attr('data-active', '').find('[type="checkbox"]').prop('checked', true);
                        $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each(function () {
                            $(this).removeClass('h-hidden')
                        });
                    }
                });

                break;

            default:
                $mainItems.each(function(index) {
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
    }

    (function init() {
        let $checked = $controlsList.find('input[type="radio"]:checked');

        findChanges($checked);
    })();

    $controlsList.on('change', 'input[type="radio"]', function() {
        findChanges($(this));
    });
}

/* ----- end ORDER ----- */