# For more information see: http://emberjs.com/guides/routing/
App.Router.map ()->
  @resource 'users'
  @resource 'locations', ->
    @route 'new',
      path: 'new'
    @route 'edit',
      path: 'edit/:location_id'
    @route 'show',
      path: '/show/:location_id'

  @resource 'events', ->
    @route 'new'

  @resource 'event', path: '/events/:event_id', ->
    @route 'edit'

  @resource 'groups', ->
    @route 'new',
      path: 'new'
    @route 'show',
      path: '/show/:group_id'
    @route 'edit',
      path: '/edit/:group_id'

App.ApplicationRoute = Ember.Route.extend
  actions:
    goBack: ->
      window.history.go -1

App.IndexRoute = Ember.Route.extend
  redirect: ->
    @transitionTo('events')

App.EventsRoute = Ember.Route.extend
  model: ->
    @store.find('event')