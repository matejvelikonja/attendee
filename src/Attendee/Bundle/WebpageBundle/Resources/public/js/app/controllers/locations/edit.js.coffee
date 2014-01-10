App.LocationsEditController = Ember.ObjectController.extend
  isNew: (->
    console.log "calculating isNew " + @get("content").get "id"
    ! @get("content").get "id"
  ).property()

  actions:
    updateItem: (location) ->
      location.transaction.commit()
      @get("target").transitionTo "locations"