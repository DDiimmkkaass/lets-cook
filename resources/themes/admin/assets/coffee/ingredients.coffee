$(document).ready () ->
  $('.incomplete-ingredients-filter').on 'change', () ->
    filter = $(this).val()
    if filter
      filter = '?filter=' + filter

    url = '/admin/ingredient/incomplete' + filter

    document.location = url

  $(document).on 'click', '.get-ingredient-quick-create', (e) ->
    e.preventDefault()

    $.ajax
      url: $(this).data('href')
      type: 'GET'
      dataType: 'json'
      error: (response) =>
        message.show response.responseText, 'error'
      success: (response) =>
        if response.status == 'success'
          dModal response.html, true

          fixCustomInputs($('#ingredient_quick_form'))
        else
          message.show response.message, response.status

    return false

  $(document).on 'click', '.ingredient-quick-store', (e) ->
    e.preventDefault()

    $form = $('#ingredient_quick_form')

    $.ajax
      url: $form.attr('action')
      type: 'POST'
      dataType: 'json'
      data: getFormData $form
      error: (response) =>
        processError response, $form
      success: (response) =>
        if response.status == 'success'
          dModalHide $form.closest('#modal')

          $select = $('.tab-pane.active').find('.ingredient-select')
          $select.append('<option selected="selected" value="' + response.ingredient.id + '">' + response.ingredient.name + '</option>')
          $select.val(response.ingredient.id).change()

        message.show response.message, response.status

    return false