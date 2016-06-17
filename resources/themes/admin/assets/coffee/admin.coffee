window.select2Options =
  minimumResultsForSearch: 10

window.initToggles = ->
 $(".status-toggler:visible").each ->
   $switcher = $(this)
   $switcher.bootstrapSwitch size: "small"

  $(".status-toggler").fieldChanger
    event: "switchChange.bootstrapSwitch"
    callback: (response) ->
      message.show response["message"], response["type"]  if response["message"]

  $(".ajax-field-changer").fieldChanger
    event: "change"
    callback: (response) ->
      message.show response["message"], response["type"]  if response["message"]

window.fixCustomInputs = ($area) ->
  initInputMask()

  initDateTimePickers()

  $area.find('span.select2').remove()
  $area.find("select.select2").each () ->
    $(this).select2(select2Options)

window.slugGenerate = ($name) ->
  if ($name.length > 1)
    $opts = $.extend({}, $.fn.syncTranslit.defaults, {})
    $str = $name
    $result = ""
    $i = 0
    while $i < $str.length
      $result += $.fn.syncTranslit.transliterate($str.charAt($i), $opts)
      $i++
    regExp = new RegExp("[" + $opts.urlSeparator + "]{2,}", "g")
    $result = $result.replace(regExp, $opts.urlSeparator)
    return $result
  else
    message.show lang_errorEmptyNameField, 'warning'
  return ""

$(document).on "click", ".front-home-link", (e) ->
  e.preventDefault()

  window.open($(this).data('href'),'_blank');

$(document).ready ->
  initToggles()

  #select
  $('select.select2').each () ->
    $(this).select2(select2Options)

  $(document).on "select2:select", (e) ->
    $row = $(e.target).closest('.field-row')

    if $row.length
      input = $row.find('.input-mask')
      input.inputmask('remove')
      _class = input.attr('class').replace /inputmask-\d/i, ''
      input.attr('class', _class)
      input.addClass('inputmask-' + e.params.data.id)
      initInputMask()

  initCheckboxes()

  $(document).on "click", ".close-me", ->
    $(this).parent().removeClass('active').fadeOut 100

  $(document).on "click", ".slug-generate", (e) ->
    e.preventDefault()

    from = $(this).data('from_id') || ".name_" + lang;
    target = $(this).data('target_id') || '#slug';
    
    $(target).val slugGenerate $(from).val()

    return false

  $(document).on "click", ".sidebar-menu .create-label", (e) ->
    e.preventDefault()

    document.location = $(this).data('href')

  $('.with-loading').on "click", () ->
    unless $(this).find('loading').length
      new Loading $(this)

    setTimeout () =>
        $form = $(this).closest 'form'

        if $form.length
          $form.submit()
     , 200

  $('.translations-table textarea').on "change", () ->
    $(this).addClass('text-bold')

  console.log "init"