App.EventIndexController = Ember.ObjectController.extend
  isPersisted: (->
    ! @get("content").get "id"
  ).property()

  actions:
    updateItem: (event) ->
      event.transaction.commit()
      @get("target").transitionTo "events"