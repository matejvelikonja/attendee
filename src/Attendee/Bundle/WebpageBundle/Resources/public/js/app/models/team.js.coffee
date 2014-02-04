App.Team = DS.Model.extend
  name:    DS.attr    'string'
  members: DS.hasMany 'user'