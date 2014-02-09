App.EventIndexController = Ember.ObjectController.extend
  needs: ["event"]
  event: Ember.computed.alias("controllers.event")

  locations: (->
    @store.find('location')
  ).property()

  isPersisted: (->
    ! @get("event").get "id"
  ).property()

  actions:
    setStatus: (attendance, status) ->
      if attendance.get("status") != status
        attendance.set "status", status
        attendance.save()

  persistOnChange: (->
    console.log('observed')
    @get('event').get('content').save()
  ).observes('event.location')