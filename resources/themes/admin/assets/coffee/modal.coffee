window.$modal = $('#modal')

window.dModal = (content, clone) ->
  clone = clone || false

  $__modals = $('.modal-clone')

  $('.modal-backdrop').each () ->
    unless $(this).hasClass 'in'
      $(this).remove()

  if $__modals.length > 0
    $__modals.each () ->
      unless $(this).hasClass 'in'
        $(this).remove()

  if clone
    $_modal = $modal.clone(false)

    $_modal.addClass('modal-clone').modal('show')

    $_modal.html content
  else
    $modal.modal('show')

    $modal.html content

  fixCustomInputs($modal)

  $('#modal').each () ->
    $(this).removeAttr 'tabindex'

window.dModalHide = ($_modal) ->
  $_modal = $_modal || false

  if $_modal
    $_modal.modal('hide')

    $_modal.remove()
  else
    $modal.modal('hide')