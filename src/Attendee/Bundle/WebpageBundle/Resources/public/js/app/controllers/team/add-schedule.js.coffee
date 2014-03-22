App.TeamAddScheduleController = Ember.ObjectController.extend
  needs: ["team"]
  team:  Ember.computed.alias("controllers.team")

  actions:
    createItem: (schedule) ->
      self = @
      schedule.save().then ->
        self.transitionToRoute 'team', self.get('team')