window.initMap = () ->
  mapDiv = document.getElementById('js-contacts-map')
  map = new (google.maps.Map)(mapDiv,
    center:
      lat: parseFloat(window.map_lat)
      lng: parseFloat(window.map_lng)
    scrollwheel: false
    zoom: 10)
  currCenter = map.getCenter()

  marker = new (google.maps.Marker)(
    position:
      lat: parseFloat(window.map_lat)
      lng: parseFloat(window.map_lng)
    map: map
    icon: 'assets/themes/lets_cook/images/contacts-map-icon.png')

  window.addEventListener 'resize', ->
    google.maps.event.trigger map, 'resize'
    map.setCenter currCenter
    return
  return