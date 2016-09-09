
/* ----- SPECIALIST REVIEW SECTION ----- */

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

/* ----- end SPECIALIST REVIEW SECTION ----- */