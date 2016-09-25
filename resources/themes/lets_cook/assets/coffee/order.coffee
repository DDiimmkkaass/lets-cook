Order = {};

Order.calculateTotal = () ->
  $total = $('#order_total_desktop')
  $total_mobile = $('#order_total_mobile')
  total = parseInt($total.data('total'))

  $discount = $('[name="coupon_code"]')
  main_discount = parseInt $discount.data('main_discount')
  additional_discount = parseInt $discount.data('additional_discount')
  discount_type = parseInt $discount.data('discount_type')

  # main basket
  if main_discount > 0
    if discount_type == 'absolute'
      total = total - main_discount
    else
      total = total - (total / 100 * main_discount)

  # ingredients
  $('.order-ing__lists .checkbox-button input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      total += parseInt $(this).data('price')

  # additional baskets
  _total = 0
  $('.order-add-more__list .checkbox-button input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      _total += parseInt($(this).data('price'))

  if _total > 0
    if additional_discount > 0
      if discount_type == 'absolute'
        _total = _total - additional_discount
      else
        _total = _total - (_total / 100 * additional_discount)

  total = total + _total

  if total < 0
    total = 0

  total += '<span>' + currency + '</span>';

  $total.html(total);
  $total_mobile.html(total);

Order.checkCoupon = (code) ->
  $.ajax
    url: '/coupons/check'
    type: "post"
    dataType: 'json'
    data:
      code: code
    error: (response) =>
      Form.processFormSubmitError(response)
    success: (response) =>
      if response.status == 'success'
        $('[name="coupon_code"]').data('main_discount', response.main_discount)
          .data('additional_discount', response.additional_discount)
          .data('discount_type', response.discount_type)
      else
        $('[name="coupon_code"]').val('')
          .data('main_discount', 0)
          .data('additional_discount', 0)
          .data('discount_type', '')

        popUp(lang_error, response.message)

      Order.calculateTotal();

Order.save = ($form) ->
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

$(document).on "ready", () ->
  if $('.order-create-form').length
    Order.calculateTotal();

  $(document).on 'click', '[name="order-promocode__submit"]', (e) ->
    e.preventDefault()

    Order.checkCoupon($('[name="coupon_code"]').val());

    return false

  $('.order-create-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()

    Order.save($(this).closest('form'));

    return false