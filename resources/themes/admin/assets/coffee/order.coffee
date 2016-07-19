order = {}

order.processCity = () ->
  $city_block = $('#order-city-block')

  unless parseInt($('.order-city-id-select').val())
    $city_block.removeClass('hidden').addClass('required').find('input').attr('required', 'required')
  else
    $city_block.addClass('hidden').removeClass('required').find('input').removeAttr('required').val('')

order.processSubscribePeriod = () ->
  $subscribe_period_block = $('#subscribe-period-block')

  if parseInt($('.order-type-select').val()) == 2
    $subscribe_period_block.removeClass('hidden').addClass('required').find('select').attr('required', 'required')
  else
    $subscribe_period_block.addClass('hidden').removeClass('required').find('select').removeAttr('required').val('')

$(document).on "ready", () ->
  #subscribe period
  order.processSubscribePeriod()

  $('.order-type-select').on "change", () ->
    order.processSubscribePeriod()

  #city
  order.processCity()

  $('.order-city-id-select').on "change", () ->
    order.processCity()

  #main basket
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
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest("tr").remove()

  #ingredients
  options = $.extend
    ajax:
      url: '/admin/ingredient/find/'
      dataType: 'json'
      delay: 0
      data: (params) ->
        return {
          text: params.term
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
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest("tr").remove()