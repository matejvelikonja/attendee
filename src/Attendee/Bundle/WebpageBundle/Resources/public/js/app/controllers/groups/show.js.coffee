App.GroupsShowController = Ember.ObjectController.extend
  isEditable: false

  isPersisted: (->
    ! @get("content").get "id"
  ).property()

  actions:
    updateItem: (group) ->
      group.transaction.commit()
      @get("target").transitionTo "groups"