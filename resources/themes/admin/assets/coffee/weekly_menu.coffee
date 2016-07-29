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

$(document).on 'ready', ->
  $('.menu-recipes-table').each () ->
    unless $(this).find('.recipe-block').length
      $(this).closest('.box-body').find('.main-recipe-helper-message').fadeOut()

  $('#week_menu_date').daterangepicker
    timePicker: false,
    format: 'DD.MM.YYYY'
    dateLimit:
      days: 7
    showWeekNumbers: true
    applyClass: 'btn-success btn-flat margin-bottom-5'
    cancelClass: 'btn-default btn-flat margin-bottom-5'
    locale:
      applyLabel: lang_select
      cancelLabel: lang_cancel
      fromLabel: lang_from
      toLabel: lang_to
      weekLabel: lang_weekLabel
      daysOfWeek: moment.weekdaysMin()
      monthNames: moment.monthsShort()
      firstDay: moment.localeData()._week.dow

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

    data = getFormData $(this).closest('.basket-select-form')

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
          else
            message.show response.message, response.status
    else
      message.show lang_basketAlreadyAddedToList, 'warning'

    return false

  $(document).on "click", ".weekly-menu-basket-remove", (e) ->
    e.preventDefault()

    $content_block = $(this).closest('.tab-pane')
    $tab = $('[href="#' + $content_block.attr('id') + '"]').closest('li')

    $tab.remove()
    $content_block.remove()

    checkLastBasketTabInList()

    return false

  $(document).on "change", '.menu-recipe-select', ->
    recipe = $(this).val()
    basket_id = $(this).data('basket')
    portions = $(this).data('portions')

    $basket = $('#basket_recipes_' + basket_id + '_' + portions)

    if recipe
      $.ajax
        url: '/admin/weekly_menu/' + basket_id + '/'  + portions + '/get-recipe-item/' + recipe
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $basket.find('#recipe_' + recipe).length
              $basket.append response.html

              fixCustomInputs($basket)
            else
              message.show lang_recipeAlreadyAddedToList, 'warning'

            if $basket.find('.recipe-block').length
              $basket.closest('.tab-pane').find('.main-recipe-helper-message').fadeIn()
          else
            message.show response.message, response.status

  $(document).on "click", ".menu-recipes-table .inner", ->
    $recipe = $(this).closest('.recipe-block')

    unless $recipe.hasClass 'main'
      $recipe.addClass 'main'
      $recipe.find('.main-checkbox').val(1)
    else
      $recipe.removeClass 'main'
      $recipe.find('.main-checkbox').val(0)

  $(document).on "click", ".menu-recipes-table .destroy", ->
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    unless ($(this).closest('.menu-recipes-table').find('.recipe-block').length - 1)
      $(this).closest('.box-body').find('.main-recipe-helper-message').fadeOut()

    $(this).closest(".recipe-block").remove()

  $('.menu-recipe-select.load').each () ->
    $.ajax
      url: '/admin/weekly_menu/' + $(this).data('basket') + '/'  + $(this).data('portions') + '/get-basket-available-recipes'
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