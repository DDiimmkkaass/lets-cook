window.dataTablaReload = ($datatable) ->
  $datatable.DataTable().ajax.reload(null, false);

$(document).ready () ->
  $('table.dataTable').on  'draw.dt', () ->
    initCheckboxes()

    $(this).find('select.select2').each () ->
      $(this).select2(select2Options)