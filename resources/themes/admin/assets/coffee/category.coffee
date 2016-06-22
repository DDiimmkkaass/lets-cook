window.delete_category = (category_id) ->
  $form = $('#admin_category_destroy_form_' + category_id)
  url = '/admin/category/' + category_id + '/get-delete-form'

  ajax_dialog(url, $form)