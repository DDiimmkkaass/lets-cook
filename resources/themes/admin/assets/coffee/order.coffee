Order = {}

Order.processCity = () ->
  $city_block = $('#order-city-block')

  unless parseInt($('.order-city-id-select').val())
    $city_block.removeClass('hidden').addClass('required').find('input').attr('required', 'required')
  else
    $city_block.addClass('hidden').removeClass('required').find('input').removeAttr('required').val('')

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

$(document).on "ready", () ->
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

  #main basket
  $('.order-basket-select').on "change", ->
    basket = $(this).val()

    if basket
      $.ajax
        url: '/admin/order/get-basket-recipes/' + basket
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            $('.order-recipe-select').html response.html

            $('.order-recipes-table [id^="recipe_"]').each () ->
              $(this).fadeOut(500, () =>
                 $(this).remove()
              );

            fixCustomInputs($(this).closest('.tab-pane'))
          else
            message.show response.message, response.status

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
  options = $.extend
    ajax:
      url: '/admin/ingredient/find/'
      dataType: 'json'
      delay: 0
      data: (params) ->
        return {
          text: params.term
          in_sales: true
        }
      processResults: (data, params) ->
        return {
          results: data
        }
      cache: true
    escapeMarkup: (markup) ->
      return markup
    minimumInputLength: 2
    templateResult: (item) ->
      return '<div id="' + item.id + '">' + item.name + '</div>'
    templateSelection: (item) ->
      return '<div id="' + item.id + '">' + item.name + '</div>'
    , select2Options

  $order_ingredients_select = $('#order_ingredient_select')
  $order_ingredients_select.select2(options)

  $order_ingredients_select.on "select2:select", () ->
    ingredient = $(this).val()

    $.ajax
      url: '/admin/order/get-ingredient-row/' + ingredient
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          unless $('.order-ingredients-table #ingredient_' + ingredient).length
            $('.order-ingredients-table').append response.html

            fixCustomInputs($('.order-ingredients-table tr:last-child'))
          else
            message.show lang_ingredientAlreadyAddedToList, 'warning'
        else
          message.show response.message, response.status

  $(document).on "click", ".order-ingredients-table .destroy", ->
    confirm_dialog () =>
        Order.deleteIngredient($(this))
