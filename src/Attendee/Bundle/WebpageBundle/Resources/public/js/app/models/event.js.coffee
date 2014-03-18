App.Event = DS.Model.extend
  name:        DS.attr      'string'
  starts_at:   DS.attr      'string'
  ends_at:     DS.attr      'string'
  location:    DS.belongsTo 'location'
  attendances: DS.hasMany   'attendance', { async: true }

  is_running: ( ->
    ends_at   = moment(@get 'ends_at')
    starts_at = moment(@get 'starts_at')
    now       = moment()
    now > starts_at and now < ends_at
  ).property('ends_at', 'starts_at')

  is_in_future: ( ->
    starts_at = moment(@get 'starts_at')
    now       = moment()
    starts_at > now
  ).property('starts_at')

  is_elapsed: (->
    moment(@get 'ends_at') < moment()
  ).property('ends_at')

  is_done: (->
    @get('attendances').filterBy('status', '').get('length') == 0 and @get('attendances').get('length') > 0
  ).property('attendances.@each')

  is_incomplete: (->
    @get('is_elapsed') and @get('attendances').filterBy('status', '').get('length') != 0
  ).property('attendances.@each', 'is_elapsed')

  present_count: (->
    @get('attendances').filterBy('is_present', true).get('length')
  ).property('attendances.@each.is_present')

  absent_count: (->
    @get('attendances').filterBy('is_absent', true).get('length')
  ).property('attendances.@each.is_absent')
