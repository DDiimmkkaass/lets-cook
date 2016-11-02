$(document).on "ready", () ->
  $(document).on 'click', '.save-card', (e) ->
    e.preventDefault()

    $form = $(this).closest('form')
    data = Form.getFormData($form)

    $.ajax
      url: $form.attr('action')
      type: 'post'
      data: data
      dataType: 'json'
      error: (response) =>
        Form.processFormSubmitError(response, $form)
      success: (response) =>
        if response.status == 'success'
          unless response.html == ''
            $(response.html).insertAfter(this)

          popUp(lang_success, response.message, null, () ->
              $('#payment_connect_form').find('form').submit()
          )
        else
          popUp(lang_error, response.message)

    return false

  $(document).on 'click', '.delete-card', () ->

    if confirm(lang_deleteCardConfirmMessage)
      $.ajax
        url: '/profiles/cards/' + $(this).data('card_id') + '/delete'
        type: 'post'
        data: {_token: $(this).data('token')}
        dataType: 'json'
        success: (response) =>
          if response.status == 'success'
            $(this).closest('.subscribe-table__row').fadeOut()

            popUp(lang_success, response.message)
          else
            popUp(lang_error, response.message)

  $(document).on 'click', '.connect-card', (e) ->
    e.preventDefault()

    $.ajax
      url: '/profiles/cards/' + $(this).data('card_id') + '/connect'
      type: 'post'
      data: {_token: $(this).data('token')}
      dataType: 'json'
      success: (response) =>
        if response.status == 'success'
          unless response.html == ''
            $(response.html).insertAfter(this)

            $('#payment_connect_form').find('form').submit()
        else if response.status == 'notice'
          popUp(lang_notice, response.message)
        else
          popUp(lang_error, response.message)

    return false