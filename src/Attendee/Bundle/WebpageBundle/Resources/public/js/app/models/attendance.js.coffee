App.Attendance = DS.Model.extend
  status:    DS.attr      'string'
  user:      DS.belongsTo 'user'
  event:     DS.belongsTo 'event'

  isPresent: (->
    @get('status') == 'present'
  ).property('status')

  isAbsent: (->
    @get('status') == 'absent'
  ).property('status')