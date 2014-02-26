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

  @resource 'teams', ->
    @route 'create',

  @resource 'team', path: '/teams/:team_id', ->
    @route 'edit',
    @route 'add-user',
    @route 'add-new-user',

App.ApplicationRoute = Ember.Route.extend
  actions:
    goBack: ->
      window.history.go -1

App.IndexRoute = Ember.Route.extend
  redirect: ->
    @transitionTo('events')

App.EventsRoute = Ember.Route.extend
  model: ->
    @store.find('event', { from: '-1 week' })

App.EventEditRoute = Ember.Route.extend
  renderTemplate: ->
    @render { controller: 'event.index' }

App.TeamsRoute = Ember.Route.extend
  model: ->
    @store.find('team')

App.TeamsCreateRoute = Ember.Route.extend
  model: ->
    @store.createRecord('team')
  deactivate: ->
    # cleanup after user leaves the route
    model = @get('currentModel')
    model.rollback() if model and not model.get('isSaving')

App.TeamAddNewUserRoute = Ember.Route.extend
  controllerName: 'TeamAddUser'
  model: ->
    @store.createRecord('user')
  deactivate: ->
    # cleanup after user leaves the route
    content = @controllerFor('TeamAddUser').get('content')
    content.rollback() if content and not content.get('isSaving')

App.LocationsRoute = Ember.Route.extend
  model: ->
    @store.find('location')