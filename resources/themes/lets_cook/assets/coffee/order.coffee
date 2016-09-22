Order = {};

Order.calculateTotal = () ->
  $total = $('#order_total_desktop')
  $total_mobile = $('#order_total_mobile')
  total = parseInt($total.data('total'))

  $('.order-add-more__list .checkbox-button input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      total += parseInt($(this).data('price'))

  $total.data('total', total);

  total += '<span>' + currency + '</span>';

  $total.html(total);
  $total_mobile.html(total);

$(document).on "ready", () ->
  if $('.order-create-form').length
    Order.calculateTotal();

  $('.order-create-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()
    
    $form = $(this).closest('form')
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

        if response.show_auth_form == true
          popUp(lang_notice, response.message)

        else
          if response.status == 'success'
            popUp(lang_success, response.message)

            unless response.html == ''
              $(response.html).insertAfter('.order-create-form')

              $('#payment_form').find('form').submit()
            else
              setTimeout () ->
                  window.location.href = '/'
                , 1500
          else
            popUp(lang_error, response.message)

    return false