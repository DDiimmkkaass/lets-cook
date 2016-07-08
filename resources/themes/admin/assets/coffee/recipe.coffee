window.delete_recipe = (recipe_id) ->
  $form = $('#admin_recipe_destroy_form_' + recipe_id)
  url = '/admin/recipe/' + recipe_id + '/get-delete-form'

  ajax_dialog(url, $form)

window.filterRecipesTable = () ->
  $table = $('#recipes_datable')
  params = [];

  $('.recipe-filter').each () ->
    params.push($(this).attr('name') + '=' + $(this).val())

  url = '/admin/recipe?' + params.join('&');

  $table.DataTable().ajax.url(url).load();

$(document).on 'ready', ->
  $('input[type=\'text\'].recipe-filter').on "keyup", ->
    filterRecipesTable()

  $('select.recipe-filter').on "change", ->
    filterRecipesTable()

  $('.ingredient-category-select').on "change", ->
    category = $(this).val()

    if category
      $.ajax
        url: '/admin/category/' + category + '/completed-ingredients'
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            $select = $('.ingredient-select')

            $select.html '<option>' + lang_pleaseSelectIngredient + '</option>';
            for ingredient in response.ingredients
              $select.append('<option value="' + ingredient.id + '">' + ingredient.name + '</option>');

            fixCustomInputs($('.ingredients-add-control'))
          else
            message.show response.message, response.status

  $('.ingredient-select').on "change", ->
    ingredient = $(this).val()

    if ingredient
      $.ajax
        url: '/admin/recipe/get-ingredient-row/' + ingredient
        type: 'GET'
        dataType: 'json'
        error: (response) =>
          processError response, null
        success: (response) =>
          if response.status is 'success'
            unless $('.recipe-ingredients-table #ingredient_' + ingredient).length
              $('.recipe-ingredients-table').append response.html

              fixCustomInputs($('.recipe-ingredients-table tr:last-child'))
            else
              message.show lang_ingredientAlreadyInAddedToList, 'warning'
          else
            message.show response.message, response.status

  $(document).on "click", ".recipe-ingredients-table .destroy", ->
    if $(this).hasClass('exist')
      id = $(this).data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest("tr").remove()