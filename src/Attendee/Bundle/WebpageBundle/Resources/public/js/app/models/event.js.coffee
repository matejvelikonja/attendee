# for more details see: http://emberjs.com/guides/models/defining-models/

App.Event = DS.Model.extend
  name:      DS.attr      'string'
  starts_at: DS.attr      'string'
  ends_at:   DS.attr      'string'
  location:  DS.belongsTo 'location'
#  attendees: DS.hasMany   'App.User'

  is_this_week: (->
    date = moment(@get 'starts_at')
    now  = moment()
    date.year() == now.year() && date.week() == now.week()
  ).property('starts_at')
