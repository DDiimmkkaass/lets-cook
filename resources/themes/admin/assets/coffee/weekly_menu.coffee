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

  $('.menu-recipe-select').on "change", ->
    recipe = $(this).val()
    basket_id = $(this).data('basket')
    $basket = $('#basket_recipes_' + basket_id)

    if recipe
      $.ajax
        url: '/admin/weekly_menu/' + basket_id + '/get-recipe-item/' + recipe
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
              $basket.closest('.box-body').find('.main-recipe-helper-message').fadeIn()
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