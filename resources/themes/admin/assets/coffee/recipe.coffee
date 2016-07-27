window.delete_recipe = (recipe_id) ->
  $form = $('#admin_recipe_destroy_form_' + recipe_id)
  url = '/admin/recipe/' + recipe_id + '/get-delete-form'

  ajax_dialog(url, $form)

$(document).on 'ready', ->
  $('.ingredient-category-select').on "change", ->
    category = $(this).val()

    $tab = $(this.closest('.tab-pane'))

    if category
      $.ajax
        url: '/admin/category/' + category + '/completed-ingredients'
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            $select = $tab.find('.ingredient-select')

            $select.html '<option>' + lang_pleaseSelectIngredient + '</option>';
            for ingredient in response.ingredients
              $select.append('<option value="' + ingredient.id + '">' + ingredient.name + '</option>');

            fixCustomInputs($tab.find('.ingredients-add-control'))
          else
            message.show response.message, response.status

  $('.ingredient-select').on "change", ->
    ingredient = $(this).val()
    type = $(this).data('type')

    $tab = $(this.closest('.tab-pane'))

    if ingredient
      $.ajax
        url: '/admin/recipe/get-ingredient-row/' + ingredient + '/' + type
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $tab.find('.recipe-ingredients-table #ingredient_' + ingredient).length
              $tab.find('.recipe-ingredients-table').append response.html

              fixCustomInputs($tab.find('.recipe-ingredients-table tr:last-child'))
            else
              message.show lang_ingredientAlreadyAddedToList, 'warning'
          else
            message.show response.message, response.status

  $(document).on "click", ".recipe-ingredients-table .destroy", ->
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest("tr").remove()