App.Event = DS.Model.extend
  name:        DS.attr      'string'
  starts_at:   DS.attr      'string'
  ends_at:     DS.attr      'string'
  location:    DS.belongsTo 'location'
  attendances: DS.hasMany   'attendance', { async: true }

  is_this_week: (->
    date = moment(@get 'starts_at')
    now  = moment()
    date.year() == now.year() and date.week() == now.week()
  ).property('starts_at')

  is_in_past: (->
    date = moment(@get 'ends_at')
    now  = moment()
    date < now
  ).property('ends_at')

  done: (->
    @get('attendances').filterBy('status', '').get('length') == 0
  ).property('attendances.@each')

  incomplete: (->
    @get('is_in_past') and @get('attendances').filterBy('status', '').get('length') != 0
  ).property('attendances.@each', 'is_in_past')

  present_count: (->
    @get('attendances').filterBy('isPresent', true).get('length')
  ).property('attendances.@each.isPresent')

  absent_count: (->
    @get('attendances').filterBy('isAbsent', true).get('length')
  ).property('attendances.@each.isAbsent')
