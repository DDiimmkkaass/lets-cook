window.delete_supplier = (supplier_id) ->
  $form = $('#admin_supplier_destroy_form_' + supplier_id)
  url = '/admin/supplier/' + supplier_id + '/get-delete-form'

  ajax_dialog(url, $form)