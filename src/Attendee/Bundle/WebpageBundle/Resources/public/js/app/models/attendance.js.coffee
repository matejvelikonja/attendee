App.Attendance = DS.Model.extend
  status: DS.attr      'string'
  event:  DS.belongsTo 'event'
