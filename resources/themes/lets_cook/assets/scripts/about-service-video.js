
/* ----- ABOUT SERVICE VIDEO ----- */

function aboutServiceVideo() {
    let $aboutService = $('.about-service'),
        $title = $aboutService.find('.about-service__title'),
        $content = $aboutService.find('.about-service__content'),
        $videoSection = $aboutService.find('.about-service__video'),
        $iframeSection = $videoSection.find('iframe'),
        $closeButton = $aboutService.find('.about-service__close');

    // VIDEO RATIO
    $iframeSection
        .attr('aspectRatio', $iframeSection.height() / $iframeSection.width())
        .removeAttr('height')
        .removeAttr('width');

    $(window).resize(function() {
        let newWidth = $videoSection.width();

        $iframeSection
            .width(newWidth)
            .height(newWidth * $iframeSection.attr('aspectRatio'));
    }).resize();

    // ABOUT SERVICE VIDEO
    $('.about-service__title').on('click', function() {
        $aboutService.attr('data-active', 'true');
        $content.attr('data-active', 'true');
        $title.attr('data-active', 'false');
    });

    // CLOSE VIDEO SECTION
    $closeButton.on('click', function() {
        $iframeSection.each(function(){
            this.contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*')
        });

        $aboutService.removeAttr('data-active');
        $content.removeAttr('data-active');
        $title.removeAttr('data-active');
    });
}

/* ----- end ABOUT SERVICE VIDEO ----- */