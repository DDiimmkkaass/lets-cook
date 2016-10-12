
/* ----- ORDER ADD MORE ----- */

function orderAddMore() {
    let $order = $('.main.order'),
        $orderAddMore = $order.find('.order-add-more'),
        $list = $orderAddMore.find('.order-add-more__list'),
        $items = $list.children(),
        $itemsMobileInfo = $items.find('.more-item__info[data-device="mobile"]'),
        $itemsDesktopInfo = $items.find('.more-item__info[data-device="desktop"]'),
        $hiddenListItems = $order.find('.order-add-more__hidden').children(),
        $popUp = $order.find('.order__add-pop-up.order-add-pop-up'),
        $popUpBgLayout = $popUp.find('.order-add-pop-up__bg-layout'),
        $popUpCancel = $popUp.find('.order-add-pop-up__cancel'),
        $popUpList = $popUp.find('.order-add-pop-up__list'),
        $popUpCheckboxes = $popUpList.find('.checkbox-button input[type="checkbox"]');

    function init() {
        $popUpCheckboxes.each(function() {
            let $that = $(this),
                $parent = $that.closest('.more-item');

            if ($that.is(':checked')) {
                $parent.attr('data-active', '');
            }
        });
    }

    function setMobileInfoHeight() {
        let maxHeight = 0;

        $itemsMobileInfo.css('height', 'auto');

        $itemsMobileInfo.each(function() {
            let $that = $(this),
                that__height = $that.outerHeight();

            if (that__height > maxHeight) {
                maxHeight = that__height;
            }
        });

        $itemsMobileInfo.outerHeight(maxHeight);
    }

    function setDesktopInfoHeight() {
        let maxHeight = 0;

        $itemsDesktopInfo.css('height', 'auto');

        $itemsDesktopInfo.each(function() {
            let $that = $(this),
                that__height = $that.outerHeight();

            if (that__height > maxHeight) {
                maxHeight = that__height;
            }
        });

        $itemsDesktopInfo.outerHeight(maxHeight);
    }


    $(window).on('resize', function() {
        init();

        setMobileInfoHeight();
        setDesktopInfoHeight();
    }).resize();

    $list.on('click', '.order-add-more__item', function() {
        let $list_that = $(this),
            list_data_more = parseInt($list_that.attr('data-more'));

        $popUpList.empty();

        $hiddenListItems.each(function() {
            let $hidden_that = $(this),
                hidden_data_more = parseInt($hidden_that.attr('data-more'));

            if (list_data_more === hidden_data_more) {
                $popUpList.append($hidden_that.clone());
            }
        });

        $popUp.attr('data-active', '');
    });

    $popUpBgLayout.add($popUpCancel).on('click', function() {
        $popUp.removeAttr('data-active');
    });

    $popUpList.on('click', '.order-add-more__item', function(e) {
        e.preventDefault();

        let $that = $(this),
            $checkbox = $that.find('input[type="checkbox"]');

        let $baskets = $('.f-order-add-more-' + $checkbox.data('id'));

        if ($checkbox.is(':checked')) {
            $baskets.each(function () {
                $(this).prop('checked', false);
                $(this).removeAttr('name');

                $(this).closest('.order-add-more__item').removeAttr('data-active');

                let $label = $(this).next();
                $label.text($label.attr('data-add'));
            });

            //remove
            $('.additional-basket-item-' + $checkbox.data('id')).remove()
        } else {
            $baskets.each(function () {
                $(this).prop('checked', true);
                $(this).attr('name', $(this).data('name'));

                $(this).closest('.order-add-more__item').attr('data-active', '');

                let $label = $(this).next();
                $label.text($label.attr('data-remove'));
            });

            //add
            let html = '<li class="order-submit__item additional-basket-item additional-basket-item-' + $checkbox.data('id') + '">' +
                '<div class="order-submit__subTitle">' + $checkbox.data('basket_name') + '</div></li>';
            $(html).insertAfter('.baskets-spliter');
        }

        setTimeout(function () {
            Order.calculateTotal();
        }, 1000);
    });
}

/* ----- end ORDER ADD MORE ----- */