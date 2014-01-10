App.MenuMainComponent = Ember.Component.extend
  tagName:           "div"
  classNames:        ["menu", "vertical", "left"]
  classNameBindings: ["isOpen:opened"]
  isOpen:            false
  containerClass:    "#container" #TODO: fix to variable
  path:              null

  actions:
    toggleMenu: ->
      @toggleProperty "isOpen"

  hasChanged: (->
    $(@get('containerClass')).toggleClass('go-right', @get('isOpen'))
  ).observes('isOpen').on('init')

  onRouteChanged: (->
    @set('isOpen', false)
  ).observes('path')