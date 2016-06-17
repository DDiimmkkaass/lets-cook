$(document).ready () ->
  $('.slim-scroll').each () ->
    $(this).slimScroll
      height: parseInt($(this).data('height')) + 'px'