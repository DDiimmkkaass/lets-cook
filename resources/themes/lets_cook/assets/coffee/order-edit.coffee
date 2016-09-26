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
    Order.calculateTotal()

  $('.order-edit-form #order-edit-coupon-id').on 'change', (e) ->
    $option = $(this).find('option:selected');

    $('.order-edit-form [name="coupon_code"]')
      .data('main_discount', $option.data('main_discount'))
      .data('additional_discount', $option.data('additional_discount'))
      .data('discount_type', $option.data('discount_type'))

    if $option.attr('data-selected') == undefined
      $('.order-edit-form [name="coupon_code"]').val($option.data('code'))
    else
      $('.order-edit-form [name="coupon_code"]').val('')

    setTimeout () ->
        Order.calculateTotal()
      , 200

  $('.order-edit-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()

    OrderEdit.save($(this).closest('form'));

    return false