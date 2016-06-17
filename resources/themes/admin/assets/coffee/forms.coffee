window.getFormData = ($form) ->
  data = new Object()
  $.each $form.serializeArray(), (i, field) ->
    data[field.name] = field.value
  return data

window.clearForm = ($form) ->
  $form.find('input, textarea').each () ->
    $(this).val ''

window.setErrors = (response, $form) ->
  time_out = 500
  n = 0

  $.each response.responseJSON, (i, item) =>
    n++
    i = i.replace('.', '_')

    $form.find('#' + i).closest('.form-group').addClass "has-error"
    setTimeout () =>
      message.show item, 'error'
    , (n * time_out)

window.processError = (response, $form) ->
  $form = $form || null

  if response.status == 422
    message.show lang_errorValidation, 'error'

    if $form
      setErrors(response, $form)
  else
    if response.message
      mess = response.message
    else
      mess = lang_errorFormSubmit

    message.show mess, 'error'

$(document).ready () ->
  $('.tab-pane .has-error').each () ->
    $parent = $(this).closest '.tab-pane'

    $('a[href=\'#' + $parent.attr('id') + '\']').closest('li').addClass 'tab-with-errors'

  $(document).on 'click', '.tab-with-errors', () ->
    $(this).removeClass('tab-with-errors')

  $(document).on 'click', '.has-error', () ->
    $(this).removeClass('has-error').find('.help-block.error').remove()

    $parent = $(this).closest '.tab-pane'

    $('a[href=\'#' + $parent.attr('id') + '\']').closest('li').removeClass 'tab-with-errors'