$(document).ready () ->
  $('.incomplete-ingredients-filter').on 'change', () ->
    filter = $(this).val()
    if filter
      filter = '?filter=' + filter

    url = '/admin/ingredient/incomplete' + filter

    document.location = url