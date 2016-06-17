
# загрузчик картинок

#  текущие (сохраненные) картинки удаляем в любом случае

#auto file upload

# загрузчик картинок
$ic = 1
uploadStart = ($fileinput) ->
  $form = $fileinput.closest("form")
  originAction = $form.attr('action')
  if($fileinput.is('[data-url]'))
    $form.attr('action', $fileinput.data('url'))
  blockId = $fileinput.attr("data-block-id")
  $block = $("#" + blockId)
  frameId = "uploadframe" + new Date().getTime()
  $tmpFrame = $("<iframe name='" + frameId + "' id='" + frameId + "' frameborder='none' width='0' height='0' style='margin:0; padding: 0;position:absolute;'></iframe>")
  $("#page-wrapper").prepend $tmpFrame
  $block.find(".progress").show()
  $block.find(".progress").find(".progress-bar").css(
    width: "0%"
  ).delay(500).animate
    width: "100%"
  , 2500
  $block.find("img.thumbnail").attr "src", "http://www.placehold.it/100x100/EFEFEF/AAAAAA&text=no+image"
  $form.attr "target", frameId
  $form.attr("target", frameId).submit()
  $form.attr('action', originAction)
  $form.removeAttr "target"
  iframeError = setInterval("load_error('" + blockId + "')", 10000)
  $tmpFrame.on "load", (data) ->
    $(this).remove()
    clearInterval iframeError
    return

  $fileinput.val ""
  return

window.uploaded = (img, thumbnail, target) ->
  $block = $("#" + target)
  if img
    $block.find("input[type=\"hidden\"]").val img
    $block.find(".product_value_image").val img
    $block.find("img.thumbnail").attr "src", thumbnail
  else
    alert "Cannot save photo"
  $block.find(".progress").hide()
  return
window.load_error = (blockId, callback) ->
  $("#" + blockId).find(".progress").find(".progress-bar").removeClass('progress-bar-success').addClass('progress-bar-danger')
  callback()
  return

$(document).ready ->
  $("body").on "click", ".upload_image", ->
    $block = $(this).closest(".fileupload-canvas")
    blockId = "fileupload" + new Date().getTime()
    $block.attr "id", blockId
    $("#gallery_image").attr("name", "gallery_image[" + blockId + "]").attr("data-block-id", blockId).click()
    return

  $("body").on "change", "input.gallery_image_uploader", ->
    uploadStart $(this)  unless $(this).val() is ""
    return

  return
