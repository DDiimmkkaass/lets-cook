(($) ->

  fieldChanger = ->

  fieldChanger.busy = false
  fieldChanger.options = {}
  fieldChanger.init = (element, params) ->
    defaults =
      callback: false
      event: "change"

    fieldChanger.options = $.extend({}, defaults, fieldChanger.options, params)
    fieldChanger.self = element
    fieldChanger.self.on fieldChanger.options.event, (e) ->
      e.preventDefault()

      id = $(this).data("id")
      url = $(this).data("url")
      token = $(this).data("token")
      field = $(this).data("field")
      value = $(this).val() || $(this).data('value')

      value = (if $(this).is(":checked") then 1 else value)

      unless value == undefined
        $.post url,
          value: value
          field: field
          _token: token
        , (response) ->
          fieldChanger.options.callback response  if typeof fieldChanger.options.callback is "function"

  $.fn.fieldChanger = (params) ->
    $(this).each ->
      unless $(this).data("fieldChanger")
        fieldChanger.init $(this), params
        $(this).data "fieldChanger", true
) jQuery