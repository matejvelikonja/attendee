App.Attendance = DS.Model.extend
  status:    DS.attr      'string'
  user_name: DS.attr      'string'
  event:     DS.belongsTo 'event'
