window.dataTablaReload = ($datatable) ->
  $datatable.DataTable().ajax.reload(null, false);

$(document).ready () ->
  $('table.dataTable').on  'draw.dt', () ->
    initCheckboxes()