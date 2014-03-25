App.TeamIndexController = Ember.ObjectController.extend
  needs: ["team"]
  team: Ember.computed.alias("controllers.team")

  actions:
    removeUser: (user) ->
      team = @get('team').get('content')
      team.get('users').then (users) ->
        if users.contains user
          users.removeObject user
          team.save()
