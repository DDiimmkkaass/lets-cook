window.dataTablaReload = ($datatable) ->
  $datatable.DataTable().ajax.reload(null, false);

window.filterDataTable = ($table) ->
  $datatable = $('#' + $table.attr('id'))
  params = [];

  $('.datatable-filter').each () ->
    params.push($(this).attr('name') + '=' + $(this).val())

  url = $datatable.DataTable().ajax.url().split('?');
  url = url[0]  + '?' + params.join('&');

  $table.DataTable().ajax.url(url).load();

$(document).ready () ->
  $('table.dataTable').on  'draw.dt', () ->
    initCheckboxes()

    $(this).find('select.select2').each () ->
      $(this).select2(select2Options)

  $('input[type=\'text\'].datatable-filter').on "keyup", ->
    filterDataTable($(this).closest('.filtered-datatable'))

  $('select.datatable-filter').on "change", ->
    filterDataTable($(this).closest('.filtered-datatable'))