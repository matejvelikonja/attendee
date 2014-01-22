App.Attendance = DS.Model.extend
  status:    DS.attr      'string'
  user_name: DS.attr      'string'
  event:     DS.belongsTo 'event'

  isPresent: (->
    @get('status') == 'present'
  ).property('status')

  isAbsent: (->
    @get('status') == 'absent'
  ).property('status')