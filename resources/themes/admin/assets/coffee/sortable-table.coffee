$(document).on "ready", () ->
  $('.table-sortable tbody').sortable
    stop: (event, ui) ->
      $(ui.item).find('.position input').val(ui.item.index() - 1)

      $.each $('.table-sortable tr'), (index) ->
        $(this).find('.position input').val(index)

  $('.table-sortable .action.create').on "click", () ->
    setTimeout () ->
        val = parseInt($('.table-sortable tr').length) - 3
        item = $('.table-sortable tr')[parseInt($('.table-sortable tr').length - 3)]

        $(item).find('.position input').val(val)
      , 500



