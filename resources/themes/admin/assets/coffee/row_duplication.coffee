window.ic = 1

window.duplicate_row = ($this) ->
  unless $this.hasClass 'duplication'
    $parent = $this.closest '.duplication'
  else
    $parent = $this

  $nrow = $parent.find(".duplicate").clone(true)
  if $nrow.length == 0
    return
  window.ic++
  $nrow[0].innerHTML = $nrow[0].innerHTML.replace(/replaseme/g, window.ic)
  $nrow.removeClass('duplicate').insertBefore $parent.find('.duplication-button')
  $nrow.find('.form-control').each () ->
    $(this).attr('name',  $(this).data('name'))
    if $(this).data 'required'
      $(this).attr('required',  $(this).data('required'))

  fixCustomInputs($nrow)

RowDuplication = {}

RowDuplication.deleteRow = ($button) ->
  if $button.hasClass('exist')
    id = $button.data("id")
    
    if id
      name = $button.data("name")
      $button.closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

  $button.closest(".duplication-row").remove()

$(document).ready ->
  window.ic = $('.duplication-row').length

  $(".duplication.duplicate-on-start").each () ->
    duplicate_row $(this)

  $(document).on "click", ".duplication .create", ->
    duplicate_row $(this)

  $(document).on "click", ".duplication .destroy", ->
    confirm_dialog () =>
        RowDuplication.deleteRow($(this))