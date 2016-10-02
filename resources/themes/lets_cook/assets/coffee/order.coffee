Order = {};

Order.generated_recipes = 100;

Order.updateRecipes = () ->
  $recipes = $('.order__pop-up .order-day-item__buttons[data-active]');

  $('.order-main__item.cloned').remove()

  if $recipes.length
    $order = $('.main.order')
    $mainItems = $order.find('.order-main__item')
    $mainItemsItems = $('.order-ing__list li')

    $mainItems.removeAttr 'data-active'
    $mainItems.find('[type="checkbox"]').prop 'checked', false
    $mainItemsItems.addClass 'h-hidden'

    $mainItemsItems.find('[type="checkbox"]').each ->
      $(this).prop('checked', false).removeAttr 'name'

    $mainItemsItems.find('label').each ->
      $(this).text $(this).data('add')

    $recipes.each () ->
      _index = $(this).data('index')
      _count = $(this).find('.order-day-item__edit').data('count')

      $mainItems.each (index) ->
        if index == _index

          $(this).attr('data-active', '').find('[type="checkbox"]').prop 'checked', true

          $('.recipe-' + $(this).find('[type=checkbox]').val() + '-ingredient').each ->
            $(this).removeClass 'h-hidden'

          if _count > 0
            i = 1
            while i < _count
              $recipe = $(this).clone()
              $recipe.addClass('cloned').find('[type="checkbox"]').attr('name', 'recipes[' + Order.generated_recipes + ']')


              $recipe.insertAfter($(this))

              i++
              Order.generated_recipes++

  price = parseInt($('#popup_total_price').text())

  $('#order_total_desktop').data('total', price)

  total_dinners = parseInt($('#total_dinners').text())
  $('#portions_count_result').text(total_dinners)

  $('.order-main__count-item [type="checkbox"]').prop('checked', false).removeAttr('checked')
  $('#order-count-radio-' + total_dinners).prop('checked', true).attr('checked', '')

  Order.calculateTotal()

Order.calculateDinners = () ->
  $total_dinners = $('#total_dinners');
  $total_price = $('#popup_total_price')

  total_dinners = 0;
  total_price = 0;

  $('.order-day-item__buttons[data-active]').each () ->
    total_dinners += $(this).find('.order-day-item__edit').data('count');

  $total_dinners.text(total_dinners)

  if total_dinners > 7
    total_price = 0
  else
    $price = $('.basket-price-' + total_dinners);

    total_price = $price.val()

  $total_price.text(total_price)

Order.calculateTotal = () ->
  $total = $('#order_total_desktop')
  $total_mobile = $('#order_total_mobile')
  $order_discount = $('#order_discount')
  $per_portion_total = $('#per_portion_total')

  recipes = parseInt $('.order-main__item[data-active]').length
  portions = parseInt $('.order-portions-count [data-active] a').text()

  total = parseInt($total.data('total'))
  order_discount = 0

  $discount = $('[name="coupon_code"]')
  main_discount = parseInt $discount.data('main_discount')
  additional_discount = parseInt $discount.data('additional_discount')
  discount_type = $discount.data('discount_type')
  # main basket

  if main_discount > 0
    if discount_type == 'absolute'
      order_discount = main_discount
      total = total - order_discount
    else
      order_discount = (total / 100 * main_discount)
      total = total - order_discount

  $total_mobile.html(Math.round(total));

  if recipes > 0
    per_portions = Math.round(total / recipes / portions)
  else
    per_portions = 0
  $per_portion_total.html(per_portions + '<span>' + currency + '</span>');

  # ingredients
  $('.order-ing__lists .checkbox-button input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      total += parseInt $(this).data('price')

  # additional baskets add
  _total = 0
  $('.order-add-more__list .checkbox-button input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      _total += parseInt($(this).data('price'))

  # additional baskets edit
  $('.order-edit__add-list .order-add-item__checkbox input[type="checkbox"]').each () ->
    if ($(this).is(':checked'))
      _total += parseInt($(this).data('price'))

  if _total > 0
    if additional_discount > 0
      if discount_type == 'absolute'
        _order_discount = additional_discount
        _total = _total - _order_discount
      else
        _order_discount = (_total / 100 * additional_discount)
        _total = _total - _order_discount


  total = Math.round(total + _total)
  order_discount = Math.round(order_discount + _order_discount)

  if total < 0
    total = 0

  if order_discount < 0 || isNaN(order_discount)
    order_discount = 0

  total += '<span>' + currency + '</span>';
  order_discount += '<span>' + currency + '</span>';

  $total.html(total);

  $order_discount.html(order_discount);

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
        $('[name="coupon_code"]')
          .data('main_discount', response.main_discount)
          .data('additional_discount', response.additional_discount)
          .data('discount_type', response.discount_type)
      else
        $('.order-create-form #order-create-coupon-id').val('').find('option:selected').removeAttr('selected')
        $('.order-create-form #order-create-coupon-id [data-last]').attr('selected', 'selected')

        $('[name="coupon_code"]').val('')
          .data('main_discount', 0)
          .data('additional_discount', 0)
          .data('discount_type', '')

        popUp(lang_error, response.message)

      Order.calculateTotal();

Order.save = ($button, $form) ->
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
  $('.order-main__count-item').on "click", (e) ->
    price = $(this).find('[type="radio"]').data('price')
    
    $('#order_total_desktop').data('total', price)

    $('#portions_count_result').text($(this).find('label').text())

    setTimeout () ->
        Order.calculateTotal()
      , 1000

  $(document).on 'click', '[name="order-promocode__submit"]', (e) ->
    e.preventDefault()

    Order.checkCoupon($('[name="coupon_code"]').val());

    return false

  $('.order-create-form #order-create-coupon-id').on 'change', (e) ->
    $option = $(this).find('option:selected');

    $('.order-create-form [name="coupon_code"]')
    .data('main_discount', $option.data('main_discount'))
    .data('additional_discount', $option.data('additional_discount'))
    .data('discount_type', $option.data('discount_type'))

    $('.order-create-form [name="coupon_code"]').val($option.data('code')).trigger('change')

  $('.order-create-form [name="coupon_code"]').on "change", () ->
    code = $(this).val()

    if code
      Order.checkCoupon(code)
    else
      $(this)
      .data('main_discount', 0)
      .data('additional_discount', 0)
      .data('discount_type', '')

      $('.order-create-form #order-create-coupon-id option:selected').removeAttr('selected')
      $('.order-create-form #order-create-coupon-id').val('')
      $('.order-create-form #order-create-coupon-id [data-last]').attr('selected', 'selected')

      Order.calculateTotal()

  $('.order-create-form').on 'click', '[name="order-submit"]', (e) ->
    e.preventDefault()

    Order.save($(this), $(this).closest('form'));

    return false