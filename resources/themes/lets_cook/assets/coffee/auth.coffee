$(document).on "ready", () ->
  $(document).on 'submit', '.sign-in__form', (e) ->
    e.preventDefault()

    $form = $(this)
    data =
      _token: $form.find('[name="_token"]').val()
      email: $form.find('[name="sign-in__mail"]').val()
      password: $form.find('[name="sign-in__pass"]').val()

    $.ajax
      url: $form.attr('action')
      type: "post"
      dataType: 'json'
      data: data
      success: (response) =>
        $('.sign-in__close').click()

        if response.status == 'success'
          popUp(lang_success, response.message, () ->
              setTimeout () ->
                  window.location.reload()
                , 1000
          )
        else
          popUp(lang_error, response.message)

    return false

  $(document).on 'submit', '.sign-out__form', (e) ->
    e.preventDefault()

    $form = $(this)
    $button = $form.find('[name="sign-out__submit"]')
    data =
      _token: $form.find('[name="_token"]').val()
      email: $form.find('[name="sign-out__email"]').val()
      password: $form.find('[name="sign-out__pass"]').val()
      full_name: $form.find('[name="sign-out__name"]').val()
      phone: $form.find('[name="sign-out__phone"]').val()
      additional_phone: $form.find('[name="sign-out__other-phone"]').val()
      gender: $form.find('[name="sign-out__radio"]:checked').val()
      birthday: $form.find('[name="sign-out__birthday"]').val()
      source: $form.find('[name="sign-out__select"]').val()

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
          $('.sign-out__close').click()

        popUp(lang_success, response.message)

    return false

  $(document).on 'submit', '.restore__form', (e) ->
    e.preventDefault()

    $form = $(this)
    data = Form.getFormData($form)

    $.ajax
      url: $form.attr('action')
      type: "post"
      dataType: 'json'
      data: data
      success: (response) =>
        $('.restore__close').click()

        if response.status == 'success'
          popUp(lang_success, response.message)

          $form.find('[name="email"]').val('')
        else
          popUp(lang_error, response.message)

    return false