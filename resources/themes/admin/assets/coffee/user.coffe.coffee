User = {}

User.addCouponRow = (html) ->
  $table = $('#coupons table')

  $table.find('tbody').append(html);

  $table.find('.no-coupons').fadeOut()

User.setActiveCouponLabel = ($coupon, _default) ->
  if _default == 1
    $('.make-coupon-default').removeClass('active').text(lang_activate)

    $coupon.addClass('active').text(lang_cancel)
  else
    $('.make-coupon-default').removeClass('active').text(lang_activate)

$(document).on "ready", () ->
  $('.save-user-coupon').on "click", () ->
    $form = $(this).closest('.user-coupon-form')

    data =
      _token: $form.find('[name="_token"]').val()
      code: $form.find('[name="code"]').val()
      user_id: $form.find('[name="coupon_user_id"]').val()

    unless data.code == ''
      $.ajax
        url: $form.data('action')
        type: "post"
        dataType: 'json'
        data: data
        error: (response) =>
          processError response, $form
        success: (response) =>
          if response.status == 'success'
            User.addCouponRow(response.html)

            $form.find('[name="code"]').val('')

            if response.default == 1
              User.setActiveCouponLabel($('#coupons .make-coupon-default').last(), 1)

          message.show response.message, response.status
    else
      message.show lang_errorEnterCouponCode, 'warning'

    return false

  $(document).on "click", '.make-coupon-default', (e) ->
    e.preventDefault()

    data =
      _token: $(this).data('token')

    $.ajax
      url: '/admin/user/' + $(this).data('user_id') + '/coupon/' + $(this).data('coupon_id') + '/default'
      type: "post"
      dataType: 'json'
      data: data
      error: (response) =>
        processError response
      success: (response) =>
        if response.status == 'success'
          User.setActiveCouponLabel($(this), response.default)

        message.show response.message, response.status

    return false