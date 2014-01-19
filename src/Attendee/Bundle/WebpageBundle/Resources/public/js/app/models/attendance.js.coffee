App.Attendance = DS.Model.extend
  status:    DS.attr      'string'
  user_name: DS.attr      'user_name'
  event:     DS.belongsTo 'event'
