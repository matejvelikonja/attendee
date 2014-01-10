Ember.Handlebars.registerBoundHelper 'humanizeDate', (date, format) ->
  return if date == null
  moment.lang('en')

  format = 'DD.MM.YYYY' if typeof format isnt 'string'

  moment(date).format(format)

