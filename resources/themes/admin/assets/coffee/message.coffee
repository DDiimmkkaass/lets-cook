message = {}
message.delay = 5000
message.closeHandler = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"
message.containeer = ".message-container"
message.info = (text) ->
  message.show text, "info"
  return

message.success = (text) ->
  message.show text, "success"
  return

message.warning = (text) ->
  message.show text, "warning"
  return

message.error = (text) ->
  message.show text, "error"
  return

message.show = (text, type) ->
  switch type
    when "info"
      type = "alert alert-info alert-dismissable"
    when "success"
      type = "alert alert-success alert-dismissable"
    when "warning"
      type = "alert alert-warning alert-dismissable"
    when "error"
      type = "alert alert-danger alert-dismissable"
    else
      type = "alert alert-info alert-dismissable"
  $("<div/>").html(text + message.closeHandler).addClass(type).appendTo(message.containeer).delay(message.delay).fadeOut 500, ->
    $(this).remove()
    return

  return