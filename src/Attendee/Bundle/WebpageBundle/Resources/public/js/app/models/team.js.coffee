App.Team = DS.Model.extend
  name:      DS.attr    'string'
  users:     DS.hasMany 'user',  { async: true }
  schedules: DS.hasMany 'schedule',  { async: true }

  users_count: (->
    @get('users').get('length')
  ).property('users.@each')
