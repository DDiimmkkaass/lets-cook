$(document).on "ready", () ->
  hash = window.location.hash.replace(/#/, '')

  unless hash is ''
    $('.article-item__tag-item:contains(' + hash + ')').first().click()