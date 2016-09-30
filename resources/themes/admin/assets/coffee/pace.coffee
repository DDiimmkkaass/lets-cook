Pace.disablePage = () ->
  width = $('html').outerWidth()
  height = $('html').outerHeight()
  
  $('.ajax-overlay').addClass('active').css('width', width).css('height', width)

Pace.enablePage = () ->
  $('.ajax-overlay').removeClass('active').css('width', 0).css('height', 0)

$(document).ready ->
  $(document).ajaxStart () ->
    Pace.restart()
    Pace.disablePage()

  $(document).ajaxStop () ->
    Pace.stop()
    Pace.enablePage()