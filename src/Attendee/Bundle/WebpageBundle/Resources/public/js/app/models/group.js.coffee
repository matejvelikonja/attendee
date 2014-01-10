# for more details see: http://emberjs.com/guides/models/defining-models/

App.Group = DS.Model.extend
  name:    DS.attr    'string'
  members: DS.hasMany 'App.User'