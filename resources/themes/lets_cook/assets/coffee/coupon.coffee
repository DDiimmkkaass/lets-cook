Coupon = {}

Coupon.addCouponRow = (row) ->
  $('.profile-coupons__table').removeClass('h-hidden').append(row)

$(document).on "ready", () ->
  $('.profile-coupons__add-new').on "submit", (e) ->
    e.preventDefault();

    $form = $(this)
    data =
      _token: $form.find('[name="_token"]').val()
      code: $form.find('[name="add-coupons-text"]').val()

    $.ajax
      url: $form.attr('action')
      type: "post"
      dataType: 'json'
      data: data
      error: (response) =>
        Form.processFormSubmitError(response, $form)
      success: (response) =>
        if response.status == 'success'
          if response.default == 1
            $('.profile-coupons__table').find('[data-chosen]').text('Не выбран').removeAttr('data-chosen')

          Coupon.addCouponRow(response.html)

          $form.find('[name="add-coupons-text"]').val('')

          popUp(lang_success, response.message)
        else
          popUp(lang_error, response.message)

    return false

  $(document).on "click", '.make-coupon-default', (e) ->
    e.preventDefault();

    data =
      _token: $(this).data('_token')
      coupon_id: $(this).data('coupon_id')

    $.ajax
      url: $(this).data('action')
      type: "post"
      dataType: 'json'
      data: data
      success: (response) =>
        if response.status == 'success'
          $(this).closest('table').find('[data-chosen]').text('Не выбран').removeAttr('data-chosen')

          $(this).text('Выбран').attr('data-chosen', '')

          popUp(lang_success, response.message)

        if response.status == 'notice'
          popUp(lang_notice, response.message)

        if response.status == 'error'
          popUp(lang_error, response.message)

    return false