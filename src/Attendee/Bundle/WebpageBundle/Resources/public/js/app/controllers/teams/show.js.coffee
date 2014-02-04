App.TeamsShowController = Ember.ObjectController.extend
  isEditable: false

  isPersisted: (->
    ! @get("content").get "id"
  ).property()

  actions:
    updateItem: (team) ->
      team.transaction.commit()
      @get("target").transitionTo "teams"