App.Location = DS.Model.extend
  name: DS.attr  'string'
  lat:  DS.attr  'number'
  lng:  DS.attr  'number'

  coordinates: (->
    if (@get 'lat') != 0 and (@get 'lng') != 0
      (@get 'lat') + ',' + (@get 'lng')
  ).property('lat', 'lng')

  mapsLink: (->
    if @get 'coordinates'
      'http://maps.googleapis.com/maps/api/staticmap?center=' + (@get 'coordinates') + '&zoom=12&size=600x300&maptype=roadmap
             &markers=color:blue%7Clabel:S%7C' + (@get 'coordinates') + '&sensor=false'
  ).property('coordinates')
