Order = {}

Order.processCity = () ->
  $city_block = $('#order-city-block')

  unless parseInt($('.order-city-id-select').val())
    $city_block.removeClass('hidden').addClass('required').find('input').attr('required', 'required')
  else
    $city_block.addClass('hidden').removeClass('required').find('input').removeAttr('required').val('')

Order.processStatus = () ->
  $status_block = $('#status-comment-block')
  $old_status = $('[name=\'old_status\']').val()

  unless parseInt($('[name=\'status\']').val()) == parseInt($('[name=\'old_status\']').val())
    $status_block.removeClass('hidden')
    if $old_status
      $status_block.addClass('required').find('textarea').attr('required', 'required')
  else
    $status_block.addClass('hidden').removeClass('required').find('textarea').removeAttr('required').val('')

Order.processSubscribePeriod = () ->
  $subscribe_period_block = $('#subscribe-period-block')

  if parseInt($('.order-type-select').val()) == 2
    $subscribe_period_block.removeClass('hidden').addClass('required').find('select').attr('required', 'required')
  else
    $subscribe_period_block.addClass('hidden').removeClass('required').find('select').removeAttr('required').val('')

Order.deleteRecipe = ($button) ->
  if $button.hasClass('exist')
    id = $button.data("id")
    if id
      name = $button.data("name")
      $button.closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

  $button.closest("tr").remove()

Order.deleteIngredient = ($button) ->
  if $button.hasClass('exist')
    id = $button.data("id")
    if id
      name = $button.data("name")
      $button.closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

  $button.closest("tr").remove()

Order.getWeeklyMenuBaskets = ($weekly_menu_select) ->
  weekly_menu_id = $weekly_menu_select.val()

  if weekly_menu_id
    $.ajax
      url: '/admin/order/get-weekly-menu-baskets/' + weekly_menu_id
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          $basket_select = $('.order-basket-select')

          $basket_select.html response.html

          new_basket_id = $('#new_basket_id').val()
          if new_basket_id
            $basket_select.val(new_basket_id)

          Order.getBasketRecipes($basket_select)

          Order.getBasketRecipesIngredients($basket_select)

          fixCustomInputs($weekly_menu_select.closest('.tab-pane'))
        else
          message.show response.message, response.status

Order.getBasketRecipes = ($basket_select) ->
  basket_id = $basket_select.val()

  if basket_id
    $.ajax
      url: '/admin/order/get-basket-recipes/' + basket_id
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          $('.order-recipe-select').html response.html

          fixCustomInputs($basket_select.closest('.tab-pane'))
        else
          message.show response.message, response.status

Order.selectRecipes = ($order_recipes_count_select) ->
  recipe_count = $order_recipes_count_select.val()

  if recipe_count
    Order.clearOldData(false);

    days = recipes_for_days[recipe_count]

    if days
      $('.order-recipe-select option').each (index, item) ->
        if days.indexOf(index) != -1
          Order.addRecipe($(this).val());
    else
      $('.order-recipe-select option').each (index, item) ->
        if index > 0 && index <= recipe_count
          Order.addRecipe($(this).val());

Order.addRecipe = (recipe_id) ->
  $.ajax
    url: '/admin/order/get-recipe-row/' + recipe_id
    type: 'GET'
    dataType: 'json'
    error: (response) =>
      processError response, null
    success: (response) =>
      if response.status is 'success'
        inserted = false
        $recipes = $('.recipe-row')

        if $recipes.length
          $recipes.each () ->
            position = $(this).data('position')

            if position > response.position && !inserted
              $(response.html).insertBefore($(this))

              inserted = true

        unless inserted
          $('.order-recipes-table').append response.html

        fixCustomInputs($('.order-recipes-table tr:last-child'))
      else
        message.show response.message, response.status

Order.getBasketRecipesIngredients = ($basket_select) ->
  basket_id = $basket_select.val()

  if basket_id
    $.ajax
      url: '/admin/order/get-basket-recipes-ingredients/' + basket_id
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          $ingredients_select = $('.order-ingredient-select')

          $ingredients_select.html response.html

          fixCustomInputs($ingredients_select.closest('.tab-pane'))
        else
          message.show response.message, response.status

Order.clearOldData = (clear_recipes_count, clear_recipes, clear_ingredients) ->
  clear_recipes_count = clear_recipes_count == undefined ? true : clear_recipes_count
  clear_recipes = clear_recipes == undefined || true : clear_recipes
  clear_ingredients = clear_ingredients == undefined || true : clear_ingredients

  if clear_recipes_count
    $('.order-recipes-count-select').val('');

  if clear_recipes
    $('.order-recipes-table [id^="recipe_"]').each () ->
      Order.deleteRecipe($(this).find('.destroy'))

  if clear_ingredients
    $('.order-ingredients-table [id^="ingredient_"]').each () ->
      Order.deleteIngredient($(this).find('.destroy'))

Order.checkBasketChange = () ->
  old_basket_id = $('#old_basket_id').val()
  new_basket_id = $('#new_basket_id').val()

  if old_basket_id && new_basket_id && old_basket_id != new_basket_id
    $('.basket-' + old_basket_id + '-recipe').each () ->
      Order.deleteRecipe($(this).find('.destroy'))

    $('.basket-' + old_basket_id + '-ingredient').each () ->
      Order.deleteIngredient($(this).find('.destroy'))

Order.getUserCoupons = (user_id) ->
  $coupon_select = $('.coupon-select')

  if user_id
    $.ajax
      url: '/admin/user/' + user_id + '/coupons'
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          $coupon_select.html response.html
        else
          message.show response.message, response.status
  else
    $('.coupon-select').find('option').each (index, item) ->
      if index > 0
        $(this).remove()

  setTimeout () ->
      fixCustomInputs($coupon_select.closest('.tab-pane'))
    , 1000

$(document).on "ready", () ->
  $('.orders-table').on 'click', '.change-status', (e) ->
    e.preventDefault()

    data =
      _token: $(this).data('_token')
      status: $(this).data('status')

    $.ajax
      url: '/admin/order/' + $(this).data('order_id') + '/update_status'
      type: 'POST'
      dataType: 'json'
      data: data
      error: (response) =>
        processError response
      success: (response) =>
        if response.status is 'success'
          $label = $(this).closest('.status-changer').find('.label')
          $label.addClass('label-' + $(this).data('status')).text($(this).data('status_label'))

          $(this).closest('.status-changer-buttons').fadeOut()

        message.show response.message, response.status

    return false;

  if $('.order-weekly-menu-select').val()
    Order.getWeeklyMenuBaskets($('.order-weekly-menu-select'))
  else
    Order.getBasketRecipes($('.order-basket-select'))

    Order.getBasketRecipesIngredients($('.order-basket-select'))

  Order.checkBasketChange()

  #user select
  $('#user_id').on "select2:select", () ->
    user_id = $(this).val()

    if user_id
      $option = $(this).find('option:selected')

      $('#full_name').val $option.data('full_name')
      $('#email').val $option.data('email')
      $('#phone').val $option.data('phone')

      $('#order_user_link').attr 'href', $option.data('link')
    else
      $('#full_name').val ''
      $('#email').val ''
      $('#phone').val ''

      $('#order_user_link').attr 'href', '#'

    Order.getUserCoupons(user_id)

  #subscribe period
  Order.processSubscribePeriod()

  $('.order-type-select').on "change", () ->
    Order.processSubscribePeriod()

  #city
  Order.processCity()

  $('.order-city-id-select').on "change", () ->
    Order.processCity()

  #comments
  Order.processStatus()

  #user coupons
  if $('#old_user').val() != $('#user_id').val()
    Order.getUserCoupons($('#user_id').val())

  $('.order-status-select').on "change", () ->
    Order.processStatus()

  $('.order-comment-form button').on "click", (e) ->
    e.preventDefault()

    $form = $(this).closest('.order-comment-form')

    data =
      order_id: $form.find('#order_id').val()
      order_comment: $form.find('#order_comment').val()

    $.ajax
      url: $form.data('action')
      type: 'POST'
      dataType: 'json'
      data: data
      error: (response) =>
        processError response, $form
      success: (response) =>
        if response.status is 'success'
          $form.find('#order_comment').val('')

          $('.comments-block').append(response.comment)

        message.show response.message, response.status

    return false

  #weekly menu
  $('.order-weekly-menu-select').on "change", ->
    Order.getWeeklyMenuBaskets($(this))

    Order.clearOldData();

  #main basket
  $('.order-basket-select').on "change", ->
    Order.getBasketRecipes($(this))

    Order.getBasketRecipesIngredients($(this))

    Order.clearOldData();

  # recipe
  $('.order-recipe-select').on "change", ->
    recipe_id = $(this).val()

    Order.addRecipe(recipe_id)

  $(document).on "click", ".order-recipes-table .destroy", ->
    confirm_dialog () =>
      Order.deleteRecipe($(this))

  $('.order-recipes-count-select').on "change", ->
    Order.selectRecipes($(this))

  $new_recipes = $('.new-recipe-row')
  if $new_recipes.length
    $recipes = $('.recipe-row')

    $new_recipes.each () ->
      inserted = false
      $_recipe = $(this)

      if $recipes.length
        $recipes.each () ->
          if $(this).data('position') > $_recipe.data('position') && !inserted
            $($_recipe).insertBefore($(this))

            inserted = true

      unless inserted
        $('.order-recipes-table').append response.html

  #ingredients
  $('.order-ingredient-select').on "change", ->
    $option = $(this).find('option:selected')

    ingredient_id = $option.data('ingredient_id')
    basket_recipe_id = $option.data('basket_recipe_id')

    if ingredient_id
      $.ajax
        url: '/admin/order/get-ingredient-row/' + basket_recipe_id + '/' + ingredient_id
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $('.order-ingredients-table #ingredient_' + basket_recipe_id + '_' + ingredient_id).length
              $('.order-ingredients-table').append response.html

              fixCustomInputs($('.order-ingredients-table tr:last-child'))
            else
              message.show lang_ingredientAlreadyAddedToList, 'warning'
          else
            message.show response.message, response.status

  $(document).on "click", ".order-ingredients-table .destroy", ->
    confirm_dialog () =>
      Order.deleteIngredient($(this))
