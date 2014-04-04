Ember.Handlebars.registerBoundHelper 'markdown', (text) ->
  return '' unless text

  new Handlebars.SafeString (marked text)
