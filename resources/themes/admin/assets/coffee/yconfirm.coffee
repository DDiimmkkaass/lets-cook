window.dialog = (title, message, $form, closure) ->
  bootbox.dialog
    title: title
    message: message
    buttons:
      main:
        label: lang_cancel
        className: "btn-default btn-flat btn-sm"
      success:
        label: lang_yes
        className: "btn-success btn-flat btn-sm"
        callback: () ->
          if typeof closure == 'function'
            closure $form
          else
            $form.submit()

window.confirm_dialog = (closure, message) ->
  message = message || lang_deleteConfirmQuestion

  bootbox.confirm
    message: message
    buttons:
      cancel:
        label: lang_cancel
        className: "btn-default btn-flat btn-sm"
      confirm:
        label: lang_confirm
        className: "btn-success btn-flat btn-sm"
    callback: (result) ->
      if result
        closure()

window.ajax_dialog = (url, $form, closure) ->
  url = url || '/admin/'
  $form = $form || null

  unless closure
    if $form is null
      closure = ->

  $.ajax
    url: url
    type: 'GET'
    error: (response) =>
      processError response, null
    success: (response) =>
      dialog(response.title, response.message, $form, closure)

$(document).ready () ->
  $(document).on "click", '.simple-link-dialog', (e) ->
    e.preventDefault();

    dialog($(this).data('title'), $(this).data('message'), null, () =>
      window.location.href = $(this).attr('href')
    )

    return false