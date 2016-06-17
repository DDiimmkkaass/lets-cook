$(document).ready () ->
  $('form').each () ->
    unless $(this).hasClass('without-js-validation')
      $(this).validator().on 'submit', (e) ->
        if (e.isDefaultPrevented())
          message.show lang_errorValidation, 'error'