$(document).on "ready", () ->
  $(document).on 'click', '.profile-edit-section__save', (e) ->
    e.preventDefault()

    $button = $(this)
    $form = $button.closest('form')
    data = Form.getFormData($form)

    $.ajax
      url: $form.attr('action')
      type: "post"
      dataType: 'json'
      data: data
      beforeSend: () =>
        $button.attr('disabled', 'disabled')
      error: (response) =>
        Form.processFormSubmitError(response, $form)
        $button.removeAttr('disabled')
      success: (response) =>
        $button.removeAttr('disabled')

        if response.status == 'success'
          setTimeout () ->
              window.location.reload()
          , 1500

          popUp(lang_success, response.message)
        else
          popUp(lang_error, response.message)

    return false