Packaging = {}

Packaging.getTab = ($tab) ->
  $content_tab = $($tab.attr('href'))

  link = $tab.data('href')

  $.ajax
    url: link
    type: 'GET'
    dataType: 'json'
    error: (response) =>
      processError response, null
    success: (response) =>
      if response.status is 'success'
        $content_tab.html response.html
      else
        message.show response.message, response.status

Packaging.updateBooklet = ($form) ->
  data = getFormData($form)

  $.ajax
    url: $form.attr('action')
    type: 'POST'
    data: data
    dataType: 'json'
    error: (response) =>
      processError response, null
    success: (response) =>
      unless response.status is 'success'
        $form.find('[name="link"]').val('')

      message.show response.message, response.status

$(document).on 'ready', () ->
  if $('.packaging-tabs').length
    $tab = $('.packaging-tabs li.active a')
    
    Packaging.getTab($tab)

  $('.ajax-tab').on 'click', (e) ->
    $tab = $(e.target)

    Packaging.getTab($tab)

  $(document).on 'submit', '.booklet-form', (e) ->
    e.preventDefault()

    console.log($(this))

    Packaging.updateBooklet($(this))

    return false