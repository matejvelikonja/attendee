App.ApplicationController = Ember.Controller.extend
  menuOpened: false

  onRouteChanged: (->
    @set 'menuOpened', false
  ).observes('currentPath')

  actions:
    toggleMenu: ->
      @set('menuOpened', !@get('menuOpened'))
