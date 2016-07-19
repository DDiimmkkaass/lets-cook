window.initDateTimePickers = () ->
  $('.datepicker-birthday').datepicker
    autoclose: true
    language: window.lang
    todayHighlight: true
    format: birthday_format
    todayBtn: true

  $('.datepicker-delivery_date').datepicker
    autoclose: true
    language: window.lang
    todayHighlight: true
    format: birthday_format
    todayBtn: true

$(document).on "ready", () ->
  initDateTimePickers()