window.loadImagePreview = (img, preview_id) ->
  isIE = (navigator.appName == "Microsoft Internet Explorer");
  path = img.value;
  ext = path.substring(path.lastIndexOf('.') + 1).toLowerCase();

  if ext == "jpeg" || ext == "jpg" ||  ext == "png"
    if isIE
      $('#'+ preview_id + ' img').attr 'src', path
      $(img).closest('.image-block').find('.remove-image').removeClass 'hidden'
    else
      if img.files[0]
        if img.files[0].size < upload_max_filesize
          reader = new FileReader()
          reader.onload = (e) ->
            $('#'+ preview_id + ' img').attr 'src', e.target.result
          reader.readAsDataURL(img.files[0])
          $(img).closest('.image-block').find('.remove-image').removeClass 'hidden'
        else
          $(".remove-image").click()
          message.show lang_errorSelectedFileIsTooLarge, 'warning'
  else
    $(".remove-image").click()
    message.show lang_errorIncorrectFileType, 'warning'

window.resetImagePreview = (preview_id, image_input_id) ->
  $('#' + preview_id + ' img').attr 'src', no_image
  
  $fileInput = $('#' + preview_id + ' input[type=file]')
  $fileInput.replaceWith($fileInput.val('').clone(true))

  $('#' + image_input_id).val ""

$(document).on "ready", () ->
  $(document).on "click", ".remove-image", () ->
    preview_id = $(this).data "preview_id"
    image_input_id = $(this).data "image_input_id"

    resetImagePreview preview_id, image_input_id

    $(this).addClass 'hidden'
