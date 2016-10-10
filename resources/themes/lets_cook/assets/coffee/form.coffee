Form = {}

Form.getFormData = ($form) ->
  data = new Object()
  $.each $form.serializeArray(), (i, field) ->
    data[field.name] = field.value
  return data

Form.processFormSubmitError = (response, $form) ->
  $form = $form || null

  if response.status == 404
    popUp(lang_error, lang_errorRequestError)
  else if response.status == 405
    popUp(lang_error, response.responseJSON.message)
  else if response.status == 422
    popUp(lang_error, Form.getErrors(response))
  else
    if response.message
      message = response.message
    else
      message = lang_errorFormSubmit
    popUp(lang_error, message)

Form.getErrors = (response) ->
  errors = ''

  $.each response.responseJSON, (i, item) =>
    errors += item.join('<br />') + '<br />'

  return errors