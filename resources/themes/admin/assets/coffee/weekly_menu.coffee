window.checkLastBasketTabInList = () ->
  setTimeout () ->
    $tabs = $('.weekly-menu-form .nav-tabs li:last')
    $tab = $($tabs[$tabs.length - 1])

    content_id = $tab.find('a:first').attr('href')
    $content = $('' + content_id)

    $('.weekly-menu-form .nav-tabs li.active').removeClass 'active'
    $('.weekly-menu-form .tab-pane.active').removeClass 'active'

    $tab.addClass 'active'
    $content.addClass 'active'

  , 300

window.updateBasketInternalPrice = ($basket) ->
  price = 0;

  $basket.find('.recipe-block').each () ->
    price += parseInt($(this).find('.recipe-price').val())

  $basket.find('.basket-internal-price').val price;

window.updateBasketRecipesPositions = ($basket) ->
  max_position = 0
  $recipe = $basket.find('.recipe-block:last-of-type')

  $basket.find('.recipe-block').each () ->
    position = parseInt $(this).find('.position-input').val()
    if position > max_position
      max_position = position

  $recipe.find('.position-input').val(max_position + 1)

WeeklyMenu = {}

WeeklyMenu.removeBasket = ($button) ->
  $content_block = $button.closest('.tab-pane')
  $tab = $('[href="#' + $content_block.attr('id') + '"]').closest('li')

  id = $button.data('id')

  unless id == 'new'
    $.ajax
      url: '/admin/order/weekly-menu-basket/' + id + '/count'
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status == 'success'
          $tab.remove()
          $content_block.remove()

          checkLastBasketTabInList()
        else
          message.show response.message, response.status

WeeklyMenu.removeRecipe = ($button) ->
  $basket = $button.closest('.tab-pane')

  if $button.hasClass('exist')
    id = $button.data("id")

    if id
      $.ajax
        url: '/admin/order/basket-recipe/' + id + '/count'
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status == 'success'
            name = $button.data("name")

            $button.closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

            $button.closest(".recipe-block").remove()

            updateBasketInternalPrice($basket)
          else
            message.show response.message, response.status
  else
    $button.closest(".recipe-block").remove()

    updateBasketInternalPrice($basket)

WeeklyMenu.addBasket = ($form, closure) ->
  closure = closure || false

  data = getFormData $form

  unless $('#basket_' + data.basket_id + '_' + data.portions).length
    $.ajax
      url: '/admin/weekly_menu/add-basket'
      type: 'GET'
      dataType: 'json'
      data: data
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status == 'success'
          dModalHide()

          $('.weekly-menu-form .nav-tabs').append(response.tab_html)
          $('.weekly-menu-form .tab-content').append(response.content_html)

          fixCustomInputs($('.weekly-menu-form'))

          checkLastBasketTabInList()

          if typeof closure == 'function'
            closure()
        else
          message.show response.message, response.status
  else
    message.show lang_basketAlreadyAddedToList, 'warning'

WeeklyMenu.addRecipe = (recipe_id, basket_id, portions, copy) ->
  copy = copy || 0

  $basket = $('#basket_recipes_' + basket_id + '_' + portions)

  unless $basket.find('#recipe_' + recipe_id).length
    $.ajax
      url: '/admin/weekly_menu/' + basket_id + '/' + portions + '/get-recipe-item/' + recipe_id + '/' + copy
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          if response.html != ''
            unless $basket.find('#recipe_' + response.recipe_id).length
              $basket.append response.html

              fixCustomInputs($basket)

              updateBasketInternalPrice($('#basket_' + basket_id + '_' + portions))

              updateBasketRecipesPositions($('#basket_' + basket_id + '_' + portions))

              if copy
                parent_basket_id = WeeklyMenu.copyParentBasketId
                parent_portions = WeeklyMenu.copyParentPortions

                $parent_recipe = $('#basket_recipes_' + parent_basket_id + '_' + parent_portions).find('#recipe_' + recipe_id)
                $recipe = $('#basket_recipes_' + basket_id + '_' + portions).find('#recipe_' + response.recipe_id)

                $recipe.find('.position-input').val $parent_recipe.find('.position-input').val()
            else
              message.show lang_recipeAlreadyAddedToList, 'warning'
        else
          message.show response.message, response.status
  else
    message.show lang_recipeAlreadyAddedToList, 'warning'

WeeklyMenu.copyBasket = () ->
  $form = $('#basket_copy_form')

  WeeklyMenu.copyNewPortions = $form.find('#portions').val()

  WeeklyMenu.addBasket($form, WeeklyMenu.copyRecipes)

WeeklyMenu.copyRecipes = () ->
  basket_id = WeeklyMenu.copyParentBasketId
  portions = WeeklyMenu.copyParentPortions

  $('#basket_recipes_' + basket_id + '_' + portions + ' .recipe-block').each () ->
    recipe_id = $(this).attr('id')
    recipe_id = recipe_id.split('_')

    if recipe_id[1]
      portions = WeeklyMenu.copyNewPortions

      WeeklyMenu.addRecipe(recipe_id[1], basket_id, portions, 1)

$(document).on 'ready', ->
  $('.tab-pane').each () ->
    updateBasketInternalPrice($(this))

  $('.weekly-menu-form .get-basket-select-popup').on "click", (e) ->
    e.preventDefault()

    $.ajax
      url: '/admin/weekly_menu/get-basket-select-popup'
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        message.show response.responseText, 'error'
      success: (response) =>
        if response.status == 'success'
          dModal response.html
        else
          message.show response.message, response.status

    return false

  $(document).on "click", ".weekly-menu-add-basket", (e) ->
    e.preventDefault()

    WeeklyMenu.addBasket($(this).closest('.basket-select-form'))

    return false

  $(document).on "click", ".weekly-menu-basket-remove", (e) ->
    e.preventDefault()

    confirm_dialog () =>
      WeeklyMenu.removeBasket($(this))

    return false

  $(document).on "click", '.menu-recipe-select', (e) ->
    e.preventDefault()

    recipe_id = $(this).data('recipe_id')
    basket_id = $(this).closest('.table').data('basket_id')
    portions = $(this).closest('.table').data('portions')

    WeeklyMenu.addRecipe(recipe_id, basket_id, portions)

    return false

  $(document).on "click", ".menu-recipes-table .destroy", ->
    confirm_dialog () =>
      WeeklyMenu.removeRecipe($(this))

  $('.menu-recipe-select.load').each () ->
    $.ajax
      url: '/admin/weekly_menu/' + $(this).data('basket') + '/' + $(this).data('portions') + '/get-basket-available-recipes'
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        processError response, null
      success: (response) =>
        if response.status is 'success'
          options = '';

          $.each response.recipes, (index, item) ->
            options += '<option value="' + item.id + '">' + item.name + '(' + lang_portionsLowercase + ': ' + item.portions + ')</option>'

          $(this).append(options);
        else
          message.show response.message, response.status

  $(document).on "click", ".copy-basket", (e) ->
    e.preventDefault()

    WeeklyMenu.copyParentBasketId = $(this).data('basket_id')
    WeeklyMenu.copyParentPortions = $(this).data('portions')

    $.ajax
      url: $(this).data('href')
      type: 'GET'
      error: (response) =>
        processError response, null
      success: (response) =>
        dialog(response.title, response.message, null, WeeklyMenu.copyBasket)

        fixCustomInputs($('#basket_copy_form'))

    return false