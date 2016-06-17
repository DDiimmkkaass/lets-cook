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

$(document).ready ->
  window.ic = $('.duplication-row').length

  $(".duplication.duplicate-on-start").each () ->
    duplicate_row $(this)

  $(document).on "click", ".duplication .create", ->
    duplicate_row $(this)

  $(document).on "click", ".duplication .destroy", ->
    $this = $(this)

    if $this.hasClass('exist')
      id = $this.data("id")
      if id
        name = $(this).data("name")
        $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"


    $(this).closest(".duplication-row").remove()