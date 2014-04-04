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

App.BaseRoute = Ember.Route.extend
  renderTemplate: (controller, model) ->
    pageTitle = ''
    if typeof @title == "function"
      pageTitle = @title controller, model

    App.TopBarView.set 'title', pageTitle

    @render()

App.LoadingRoute = App.BaseRoute.extend
  title: ->
    ''

App.ApplicationRoute = App.BaseRoute.extend
  actions:
    goBack: ->
      window.history.go -1

App.IndexRoute = App.BaseRoute.extend
  redirect: ->
    @transitionTo('events')

App.EventsRoute = App.BaseRoute.extend
  title: ->
    'events'
  model: ->
    @store.find('event', { from: '-1 week' })

App.EventRoute = App.BaseRoute.extend
  title: (controller, model) ->
    model.get 'name'

App.EventEditRoute = App.BaseRoute.extend
  renderTemplate: ->
    @render { controller: 'event.index' }
  deactivate: ->
    model = @get('currentModel')
    model.rollback() if model and not model.get('isSaving')

App.TeamsRoute = App.BaseRoute.extend
  title: ->
    'teams'
  model: ->
    @store.find('team')

App.TeamRoute = App.BaseRoute.extend
  title: (controller, model) ->
    model.get 'name'

App.TeamsCreateRoute = App.BaseRoute.extend
  model: ->
    @store.createRecord('team')
  deactivate: ->
    # cleanup after user leaves the route
    model = @get('currentModel')
    model.rollback() if model and not model.get('isSaving')

App.TeamAddNewUserRoute = App.BaseRoute.extend
  controllerName: 'TeamAddUser'
  model: ->
    @store.createRecord('user')
  deactivate: ->
    # cleanup after user leaves the route
    content = @controllerFor('TeamAddUser').get('content')
    content.rollback() if content and not content.get('isSaving')

App.LocationsRoute = App.BaseRoute.extend
  model: ->
    @store.find('location')