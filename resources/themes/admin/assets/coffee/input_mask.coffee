window.initInputMask = () ->
  $(".inputmask-birthday").each () ->
    $(this).inputmask(birthday_format, {'placeholder': birthday_format})

  $(".inputmask-delivery_date").each () ->
    $(this).inputmask(birthday_format, {'placeholder': birthday_format})

  #mobile phone
  $(".inputmask-2").each () ->
    $(this).inputmask
      mask: "+9999999999999999"
      greedy: false
      placeholder: ""

  $(".inputmask-3").each () ->
    $(this).inputmask
      mask: '999 999 999 999'
      placeholder: ' '
      numericInput: true
      rightAlign: false

  $(".inputmask-timepicker").each () ->
    $(this).inputmask
      mask: '99:99'

$(document).on "ready", () ->
  initInputMask()