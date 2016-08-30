window.initTimePickers = () ->
  $('.timepicker').timepicker
    defaultTime: false
    showMeridian: false
    maxHours: 24
    minuteStep: 1

$(document).on "ready", () ->
  $('.timepicker-icon').on "click", () ->
    $(this).closest('.timepicker').find('input').click()