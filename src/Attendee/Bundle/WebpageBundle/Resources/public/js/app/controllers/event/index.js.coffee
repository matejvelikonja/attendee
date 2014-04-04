App.EventIndexController = Ember.ObjectController.extend
  needs: ["event"]
  event: Ember.computed.alias("controllers.event")

  locations: (->
    @store.find('location')
  ).property()

  # hack, because ember doesn't mark changed relationships as dirty
  persistOnChange: (->
    @get('event').get('content').send('becomeDirty')
  ).observes('event.location')

  actions:
    setStatus: (attendance, status) ->
      if attendance.get("status") != status
        attendance.set "status", status
        attendance.save()

    save: ->
      event = @get('event').get('content')
      self = @
      event.save().then ->
        self.transitionToRoute 'event.index', event