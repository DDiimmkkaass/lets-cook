Order = {}

Order.processCity = () ->
  $city_block = $('#order-city-block')

  unless parseInt($('.order-city-id-select').val())
    $city_block.removeClass('hidden').addClass('required').find('input').attr('required', 'required')
  else
    $city_block.addClass('hidden').removeClass('required').find('input').removeAttr('required').val('')

Order.processStatus = () ->
  $status_block = $('#status-comment-block')

  unless parseInt($('[name=\'status\']').val()) == parseInt($('[name=\'old_status\']').val())
    $status_block.removeClass('hidden').addClass('required').find('textarea').attr('required', 'required')
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

$(document).on "ready", () ->
  Order.getBasketRecipes($('.order-basket-select'))

  Order.getBasketRecipesIngredients($('.order-basket-select'))

  #user select
  $('#user_id').on "select2:select", () ->
    if $(this).val()
      $option = $(this).find('option:selected')

      $('#full_name').val $option.data('full_name')
      $('#email').val $option.data('email')

      $('#order_user_link').attr 'href', $option.data('link')
    else
      $('#full_name').val ''
      $('#email').val ''

      $('#order_user_link').attr 'href', '#'

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

  #main basket
  $('.order-basket-select').on "change", ->
    Order.getBasketRecipes($(this))

    Order.getBasketRecipesIngredients($(this))

    $('.order-recipes-table [id^="recipe_"]').each () ->
      $(this).fadeOut(500, () =>
        $(this).remove()
      );

    $('.order-ingredients-table [id^="ingredient_"]').each () ->
      $(this).fadeOut(500, () =>
        $(this).remove()
      );


  $('.order-recipe-select').on "change", ->
    recipe = $(this).val()

    if recipe
      $.ajax
        url: '/admin/order/get-recipe-row/' + recipe
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $('.order-recipes-table #recipe_' + recipe).length
              $('.order-recipes-table').append response.html

              fixCustomInputs($('.order-recipes-table tr:last-child'))
            else
              message.show lang_recipeAlreadyAddedToList, 'warning'
          else
            message.show response.message, response.status

  $(document).on "click", ".order-recipes-table .destroy", ->
    confirm_dialog () =>
      Order.deleteRecipe($(this))

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
