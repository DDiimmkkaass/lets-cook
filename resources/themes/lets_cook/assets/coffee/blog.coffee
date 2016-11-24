$(document).on "ready", () ->
  hash = window.location.hash.replace(/#/, '')

  $('.article-item__tag-item:contains(' + hash + ')').first().click()