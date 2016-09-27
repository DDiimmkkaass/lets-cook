$(document).on 'ready', () ->
  $(document).on 'click', '.subscribe-table__change', () ->
    window.location.href = $(this).find('div').data('href')

  $(document).on 'click', '.basket-subscribes-form .delete-tmpl-order', () ->
    $.ajax
      url: '/order/' + $(this).data('order_id') + '/delete'
      type: 'post'
      data: {_token: $(this).data('token')}
      dataType: 'json'
      success: (response) =>
        if response.status == 'success'
          $(this).closest('.subscribe-table__row').fadeOut()
          popUp(lang_success, response.message)
        else
          popUp(lang_error, response.message)

  $(document).on 'click', '.basket-subscribes-form .basket-subscribes-form-submit', () ->
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
          popUp(lang_success, response.message)

          setTimeout () ->
              window.location.reload()
            , 1500
        else
          popUp(lang_error, response.message)

  $(document).on 'click', '.basket-subscribes-form .basket-subscribes-unsubscribe', () ->
    $.ajax
      url: $(this).data('href')
      type: 'post'
      dataType: 'json'
      success: (response) =>
        if response.status == 'success'
          popUp(lang_success, response.message)

          setTimeout () ->
            window.location.reload()
          , 1500
        else
          popUp(lang_error, response.message)