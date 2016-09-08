$(document).on "ready", () ->
  $('.order-create-form').on 'submit', (e) ->
    e.preventDefault()
    
    $form = $(this)
    data = Form.getFormData($form)

    $.ajax
      url: $form.attr('action')
      type: 'post'
      dataType: 'json'
      data: data
      error: (response) =>
        Form.processFormSubmitError(response, $form)
      success: (response) =>
        if response.status == 'success'
          popUp(lang_success, response.message)
        else
          popUp(lang_error, response.message)

    return false