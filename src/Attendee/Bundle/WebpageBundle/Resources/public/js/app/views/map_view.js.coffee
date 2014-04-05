App.MapView = Ember.View.extend
  templateName:      'views/map'
  attributeBindings: ['lat', 'lng'],

  click: ->
    url = 'http://maps.google.com/?q=' + @get('coordinates')
    window.open url,'_blank'

  coordinates: (->
    if (@get 'lat') != 0 and (@get 'lng') != 0
      (@get 'lat') + ',' + (@get 'lng')
  ).property('lat', 'lng')

  src: (->
    url = 'http://maps.googleapis.com/maps/api/staticmap?center=' + (@get 'coordinates') + '&zoom=12&size=600x300&maptype=roadmap
                 &markers=color:blue%7Clabel:S%7C' + (@get 'coordinates') + '&sensor=false'

    url.replace(/\s+/g, '')
  ).property('coordinates')