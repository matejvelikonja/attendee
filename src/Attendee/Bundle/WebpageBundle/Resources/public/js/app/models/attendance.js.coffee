App.Attendance = DS.Model.extend
  status:    DS.attr      'string'
  user:      DS.belongsTo 'user'
  event:     DS.belongsTo 'event'

  is_editable: (->
    @get('event.is_running') or @get('event.is_elapsed')
  ).property('event.is_running', 'event.is_elapsed')

  is_present: (->
    @get('status') == 'present'
  ).property('status')

  is_absent: (->
    @get('status') == 'absent'
  ).property('status')