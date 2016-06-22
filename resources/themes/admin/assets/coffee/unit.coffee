window.delete_unit = (unit_id) ->
  $form = $('#admin_unit_destroy_form_' + unit_id)
  url = '/admin/unit/' + unit_id + '/get-delete-form'

  ajax_dialog(url, $form)