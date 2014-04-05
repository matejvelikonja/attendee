App.TeamAddUserController = Ember.ObjectController.extend
  needs: ["team"]
  team:  Ember.computed.alias("controllers.team")

  users: (->
    Ember.ArrayProxy.createWithMixins Ember.SortableMixin, {
      sortProperties: ['name']
      content:        @store.find('user')
    }
  ).property()

  actions:
    saveUser: (user) ->
      self = @
      user.save().then ->
        self.send 'addUser', user
    addUser: (user) ->
      team = @get('team').get('content')
      self = @
      team.get('users').then (users) ->
        unless users.contains user
          users.pushObject user
          team.save().then ->
            self.set 'content', null
            self.transitionToRoute 'team.index', team