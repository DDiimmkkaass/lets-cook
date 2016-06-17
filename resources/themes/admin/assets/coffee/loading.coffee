class Loading
  constructor: (@obj) ->
    $loader = $('<div id="loader" class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></div>').appendTo @obj
    position = @obj.css 'position'

    if position != 'absolute' && position != 'fixed' && position != 'relative'
      @obj.css 'position', 'relative'

  hide: () ->
    @obj.find('.loading').remove()
