$(document).on "ready", () ->
  $(document).on 'click', '.show-auth-form', (e) ->
    e.preventDefault()

    $('.header .header__sign-in').attr('data-active', '')

    return false

  $('.social-likes').on 'counter.social-likes', (e, service, number) ->
    if parseInt(number) == 0
      $('.social-likes__widget_' + service + ' .social-likes__counter').text('0')

  console.log('init')
