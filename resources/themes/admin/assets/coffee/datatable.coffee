window.dataTablaReload = ($datatable) ->
  $datatable.DataTable().ajax.reload(null, false);

window.filterDataTable = ($table) ->
  $datatable = $('#' + $table.attr('id'))
  params = [];

  $table.find('.datatable-filter').each () ->
    params.push($(this).attr('name') + '=' + $(this).val())

  url = $datatable.DataTable().ajax.url()

  url = $datatable.DataTable().ajax.url().split('?');
  url = url[0]  + '?' + params.join('&');

  $table.DataTable().ajax.url(url).load()

$(document).ready () ->
  $('table.dataTable').on  'draw.dt', () ->
    fixCustomInputs($(this))

  $(document).on "keyup", 'input[type=\'text\'].datatable-filter', ->
    filterDataTable($(this).closest('.filtered-datatable'))

  $(document).on "change", 'input[type=\'text\'].datatable-date-filter', ->
    filterDataTable($(this).closest('.filtered-datatable'))

  $(document).on "change", 'select.datatable-filter', ->
    filterDataTable($(this).closest('.filtered-datatable'))