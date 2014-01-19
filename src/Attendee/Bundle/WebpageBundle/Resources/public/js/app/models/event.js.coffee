App.Event = DS.Model.extend
  name:        DS.attr      'string'
  starts_at:   DS.attr      'string'
  ends_at:     DS.attr      'string'
  location:    DS.belongsTo 'location'
  attendances: DS.hasMany   'attendance', { async: true }

  is_this_week: (->
    date = moment(@get 'starts_at')
    now  = moment()
    date.year() == now.year() && date.week() == now.week()
  ).property('starts_at')
