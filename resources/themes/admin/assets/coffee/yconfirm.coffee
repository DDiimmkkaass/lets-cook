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

$(document).ready () ->
  $(document).on "click", '.simple-link-dialog', (e) ->
    e.preventDefault();

    dialog($(this).data('title'), $(this).data('message'), null, () =>
        window.location.href = $(this).attr('href')
    )

    return false