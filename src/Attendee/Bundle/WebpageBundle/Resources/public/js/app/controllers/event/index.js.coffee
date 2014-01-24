App.EventController = Ember.ObjectController.extend
  isPersisted: (->
    ! @get("content").get "id"
  ).property()

  actions:
    updateItem: (event) ->
      event.transaction.commit()
      @get("target").transitionTo "events"

    setStatus: (attendance, status) ->
      if attendance.get("status") != status
        attendance.set "status", status
        attendance.save()