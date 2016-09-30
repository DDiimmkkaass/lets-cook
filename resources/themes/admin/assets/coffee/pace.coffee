Pace.disablePage = () ->
  width = $('html').outerWidth()
  height = $('.main-sidebar').outerHeight()

  $('.ajax-overlay').addClass('active').css('width', width).css('height', height)

Pace.enablePage = () ->
  $('.ajax-overlay').removeClass('active').css('width', 0).css('height', 0)

$(document).ajaxStart () ->
  Pace.restart()
  Pace.disablePage()

$(document).ajaxStop () ->
  Pace.stop()
  Pace.enablePage()
