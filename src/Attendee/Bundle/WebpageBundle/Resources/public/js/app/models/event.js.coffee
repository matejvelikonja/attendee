# for more details see: http://emberjs.com/guides/models/defining-models/

App.Event = DS.Model.extend
  name:      DS.attr      'string'
  date:      DS.attr      'string' # Date can't be date type, otherwise it doesn't work on saving. Don't know why.
  location:  DS.belongsTo 'App.Location'
  attendees: DS.hasMany   'App.User'

  is_this_week: (->
    date = moment(@get 'date')
    now  = moment()
    date.year() == now.year() && date.week() == now.week()
  ).property('date')
