OrderEdit = {};

OrderEdit.save = ($form) ->
  $button = $(this)
  data = Form.getFormData($form)

  $.ajax
    url: $form.attr('action')
    type: 'post'
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
        popUp(lang_success, response.message)

        unless response.html == ''
          $(response.html).insertAfter('.order-edit-form')

          $('#payment_form').find('form').submit()
        else
          setTimeout () ->
            window.location.reload()
          , 1500
      else
        popUp(lang_error, response.message)

$(document).on "ready", () ->
  $('.order-edit-form .order-add-item__checkbox [type="checkbox"]').on 'change', (e) ->
    if $(this).is(":checked")
      operation = 'add'
    else
      operation = 'sub'

    updateOrderTotal($(this).data('price'), operation)

  $('.order-edit-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()

    OrderEdit.save($(this).closest('form'));

    return false