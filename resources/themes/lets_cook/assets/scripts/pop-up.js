
/* ----- COMMON POP UP ----- */

function popUp(title = '', content = '', callback = function() {}) {
    let $popUp = $('.pop-up'),
        $title = $popUp.find('.pop-up__title'),
        $content = $popUp.find('.pop-up__content');

    $title.text(title);
    $content.html(content);
    $popUp.attr('data-active', '');
    $popUp.attr('style', 'z-index: 999999');

    // CLOSE POPUP
    $popUp.off().on('click', '.pop-up__close-layout, .pop-up__close', function() {
        $popUp.removeAttr('data-active');

        setTimeout(function () {
            $title.empty();
            $content.empty();
        }, 1000);
    });

    callback();
}

/* ----- end COMMON POP UP ----- */