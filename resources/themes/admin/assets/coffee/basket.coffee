$(document).on 'ready', ->
  $('.recipe-select').on "change", ->
    recipe = $(this).val()

    if recipe
      $.ajax
        url: '/admin/basket/get-recipe-row/' + recipe
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $('.basket-recipes-table #recipe_' + recipe).length
              $('.basket-recipes-table').append response.html

              fixCustomInputs($('.basket-recipes-table tr:last-child'))
            else
              message.show lang_recipeAlreadyAddedToList, 'warning'
          else
            message.show response.message, response.status

  $(document).on "click", ".basket-recipes-table .destroy", ->
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest("tr").remove()