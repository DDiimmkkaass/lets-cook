$(document).ready () ->
  $('.save-variable-value').on "click", (e) ->
    e.preventDefault()

    $form = $(this).closest('form')

    for instance of CKEDITOR.instances
      value = CKEDITOR.instances[instance].getData()
      value.replace('\r\n', '')
      $('#' + instance).val(value)

    data = getFormData $form

    $.ajax
      url: $form.attr('action')
      type: 'POST'
      dataType: 'json'
      data: data
      error: (response) =>
        processError response, $form
      success: (response) =>
        message.show response.message, response.status

    return false