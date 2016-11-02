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

OrderEdit.cancelOrder = ($order) ->
  if confirm(lang_youReallyWantToCancelThisOrder)
    $.ajax
      url: '/order/' + $order.data('order_id') + '/delete'
      type: 'post'
      data: {_token: $order.data('token')}
      dataType: 'json'
      success: (response) =>
        if response.status == 'success'
          popUp(lang_success, response.message)

          if $('.order-edit-form').length
            window.location = '/profiles/orders'
          else
            $order = $order.closest('.profile-orders-own__item')

            $order.fadeOut(600)

            setTimeout () ->
                $order.remove()
              , 800
        else
          popUp(lang_error, response.message)

$(document).on "ready", () ->
  $('.order-edit-form .order-add-item__checkbox [type="checkbox"]').on 'click', (e) ->
    $checkbox = $(this)

    if $checkbox.data('_disabled') == 'disabled'
      e.preventDefault()
    else
      Order.calculateTotal()

  $('.order-edit-form #order-edit-coupon-id').on 'change', (e) ->
    $option = $(this).find('option:selected');

    $('.order-edit-form [name="coupon_code"]')
      .data('main_discount', $option.data('main_discount'))
      .data('additional_discount', $option.data('additional_discount'))
      .data('discount_type', $option.data('discount_type'))

    if $option.attr('data-selected') != undefined
      $('.order-edit-form [name="coupon_code"]').val('')
      $('#order-edit-check-coupon').attr('disabled', 'disabled').html('Скидка учтена')

      Order.calculateTotal()
    else
      $('.order-edit-form [name="coupon_code"]').val($option.data('code'))

      $('#order-edit-check-coupon').removeAttr('disabled').text('Пересчитать')

  $('.order-edit-form [name="coupon_code"]').on "change", () ->
    $option = $('#order-edit-coupon-id').find('option:selected');

    if $option.attr('data-selected') == undefined
      $('#order-edit-check-coupon').removeAttr('disabled').text('Пересчитать')

      if $(this).val() == ''
        Order.unselectCoupon()

  $(document).on 'click', '#order-edit-check-coupon', (e) ->
    e.preventDefault()

    unless $(this).attr('disabled')
      Order.checkCoupon($('[name="coupon_code"]').val(), $(this), true);

    return false

  $('.order-edit-form [name="basket_id"]').on "change", () ->
    $option = $(this).find('option:selected')

    $('#order_total_desktop').data('total', $option.data('price'))

    Order.calculateTotal()

  $('.order-edit-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()

    OrderEdit.save($(this).closest('form'));

    return false

  $('.order-edit-form').on 'click', '.order-edit__cancel-order', (e) ->
    e.preventDefault()

    OrderEdit.cancelOrder($(this));

    return false

  $(document).on 'click', '.own-order__cancel-button', (e) ->
    e.preventDefault()

    OrderEdit.cancelOrder($(this));

    return false