$(document).on "ready", () ->
  $(document).on 'click', '.show-auth-form', (e) ->
    e.preventDefault()

    $('.header .header__sign-in').attr('data-active', '')

    return false

  console.log('init')