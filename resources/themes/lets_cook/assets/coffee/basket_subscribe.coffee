BasketSubscribe = {}

BasketSubscribe.tableHeight = 0

BasketSubscribe.initPage = () ->
  $table = $('.subscribe-table')
  $rows = $table.find('.subscribe-table__row')

  height = 0

  $rows.each (index, item) ->
    if index <= 1
      height += parseInt($(this).outerHeight())

  BasketSubscribe.tableHeight = height

  $table.css('height', height + 'px').removeClass('full')
  $('.h-subscribes-form-show-all').text($('.h-subscribes-form-show-all').data('show'))

$(document).on 'ready', () ->
  $(document).on 'click', '.h-subscribes-form-show-all', () ->
    $table = $('.subscribe-table')

    if $table.hasClass('full')
      $table.css('height', BasketSubscribe.tableHeight + 'px').removeClass('full')
      $(this).text($(this).data('show'))
    else
      $table.css('height', 'auto').addClass('full')
      $(this).text($(this).data('hide'))

    $('.profile-orders-content__tabs-item').css('height', 'auto');
    $parent = $table.closest('.profile-orders-content__tabs-item')
    $tab = $parent.find('.profile-orders-content__tabs-title')
    $main = $parent.find('.profile-orders-content__main')

    $parent.outerHeight($tab.outerHeight() + $main.outerHeight())

  $(document).on 'click', '.subscribe-table__change', () ->
    window.location.href = $(this).find('div').data('href')

  $(document).on 'click', '.basket-subscribes-form .delete-tmpl-order', () ->
    if confirm(lang_youReallyWantToCancelThisOrder)
      $.ajax
        url: '/order/' + $(this).data('order_id') + '/delete'
        type: 'post'
        data: {_token: $(this).data('token')}
        dataType: 'json'
        success: (response) =>
          if response.status == 'success'
            $(this).closest('.subscribe-table__row').fadeOut()
            popUp(lang_success, response.message)
          else
            popUp(lang_error, response.message)

  $(document).on 'click', '.basket-subscribes-form .basket-subscribes-form-submit', () ->
    $form = $(this).closest('form')
    data = Form.getFormData($form)

    $.ajax
      url: $form.attr('action')
      type: 'post'
      data: data
      dataType: 'json'
      error: (response) =>
        Form.processFormSubmitError(response, $form)
      success: (response) =>
        if response.status == 'success'
          popUp(lang_success, response.message)

          setTimeout () ->
              window.location.reload()
            , 1500
        else
          popUp(lang_error, response.message)

  $(document).on 'click', '.basket-subscribes-form .basket-subscribes-unsubscribe', () ->
    $.ajax
      url: $(this).data('href')
      type: 'post'
      dataType: 'json'
      success: (response) =>
        if response.status == 'success'
          popUp(lang_success, response.message)

          setTimeout () ->
            window.location.reload()
          , 1500
        else
          popUp(lang_error, response.message)