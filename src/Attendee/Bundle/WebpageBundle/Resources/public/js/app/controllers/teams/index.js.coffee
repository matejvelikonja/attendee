App.TeamsIndexController = Ember.ArrayController.extend
  needs: ["teams"]
  teams: Ember.computed.alias("controllers.teams")
