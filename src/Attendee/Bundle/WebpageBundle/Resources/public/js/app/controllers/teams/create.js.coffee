App.TeamsCreateController = Ember.ObjectController.extend
  actions:
    createItem: (team) ->
      self = @
      team.save().then ->
        self.transitionToRoute 'team', team
