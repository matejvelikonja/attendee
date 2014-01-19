App.Group = DS.Model.extend
  name:    DS.attr    'string'
  members: DS.hasMany 'App.User'