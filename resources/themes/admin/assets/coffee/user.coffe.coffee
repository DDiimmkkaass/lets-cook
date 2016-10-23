User = {}

User.addCouponRow = (html) ->
  $table = $('#coupons table')

  $table.find('tbody').append(html);

  $table.find('.no-coupons').fadeOut()

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

          message.show response.message, response.status
    else
      message.show lang_errorEnterCouponCode, 'warning'

    return false