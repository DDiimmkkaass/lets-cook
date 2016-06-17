fields_count = 1

window.addUserField = ($button) ->
  $field_row = $button.find(".duplicate").clone(true)
  $ic++
  $field_row[0].innerHTML = $field_row[0].innerHTML.replace(/replaceme/g, $ic)
  $field_row.removeClass('duplicate').insertBefore '.add-field-button-block'

  fixCustomInputs($field_row)

$(document).ready ->
  fields_count = $('.field-row').length || fields_count

  $(document).on "click", ".add-field-button", () ->
    addUserField($(this))

  $(document).on "click", ".remove-field-button", ->
    id = $(this).data("id")
    if id
      name = $(this).data("name")
      $(this).closest("form").append "<input type=\"hidden\" name=\"" + name + "\" value=\"" + id + "\" />"

    $(this).closest(".field-row").fadeOut ->
      $(this).remove()

  return
