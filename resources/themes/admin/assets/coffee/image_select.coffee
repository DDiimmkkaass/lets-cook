$(document).ready () ->
  $(document).on 'click', '.show-elfinder-button', () ->
    $button = $(this)

    $('<div >').dialog
      modal: true
      width: '80%'
      title: $button.data('title')
      open: (event, ui) ->
        $('.ui-dialog').css('z-index', 9999)
        $('.ui-widget-overlay').css('z-index', 9998)
      create: (event, ui) ->
        $(this).elfinder(
          resizable: false
          url: elfinderConnectorUrl
          commandsOptions:
            getfile:
              oncomplete: 'destroy'
          getFileCallback: (file) ->
            _file = '/' + file.path
            $($button.data('target')).val(_file).trigger("change")

            $download_button = $button.parent().find('.download-file-button a')
            if $download_button.length
              $download_button.attr('href', $download_button.data('href') + '=' + _file)

            $('button.ui-dialog-titlebar-close').click()
            return
        ).elfinder 'instance'
        return

  $(document).find('.image_input_thumbnail').on 'error', ->
    $(this).attr 'src', $(this).data('default')

  $(document).on 'change', 'input[data-related-image]', () ->
    $this = $(this)
    $image = $($this.data('related-image'))

    _src = $(this).val()

    $image.html ''
    $image.attr 'src', _src || $image.data('default')

    $image.on 'error', ->
      $image.attr 'src', $image.data('default')

  $(document).on 'click', '.clear-image-button', () ->
    $this = $(this)
    $image = $($this.data('target-image'))
    $input = $($this.data('target-input'))

    $image.attr 'src', $image.data('default')
    $input.val('')