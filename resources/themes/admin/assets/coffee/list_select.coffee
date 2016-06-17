window.processListSelect = (href, data) ->
  $.ajax
    url: href
    type: 'POST'
    dataType: 'json'
    data: data
    error: (response) =>
      processError response, null
    success: (response) =>
      message.show response.message, response.status

window.getListSelectedData = ($block) ->
  data = {}
  items = []

  $list = $block.find('.list-select-item:checked')

  unless $list.length
    return null

  $list.each () ->
    items.push($(this).val())

  data.items = items

  $block.find('.list-select-data-item').each () ->
    data[$(this).data('name')] = $(this).val()

  return data

$(document).ready () ->
  $('.list-select-button').on "click", (e) ->
    e.preventDefault()

    $block = $(this).closest('.list-select-block')

    $option = $block.find('.list-select-action option:selected')

    href = $option.data('href') || null
    closure = $option.data('closure') || null

    if !href && !closure
      return false
      
    data = getListSelectedData($block)

    unless data
      message.show lang_errorEmptyData, 'warning'
      return false

    unless closure
      processListSelect href, data
    else
      window[closure](href, data)

    return false