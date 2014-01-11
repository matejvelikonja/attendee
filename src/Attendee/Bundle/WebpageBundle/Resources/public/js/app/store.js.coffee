# http://emberjs.com/guides/models/defining-a-store/

App.ApplicationAdapter = DS.RESTAdapter.extend
  # append json at the end, so we make sure we have json response every time
  ajax: (url, type, hash) ->
    if type isnt 'GET' and hash.data
      # CSRF-Token passing to all requests
      hash.data['authenticity_token'] = $('meta[name="csrf-token"]').attr('content')
    @_super(url, type, hash)
  namespace: 'api',
#  buildURL: ->
#    normalURL = @_super.apply(@, arguments)
#    normalURL + '.json'

App.Store = DS.Store.extend
  revision: 12
  adapter: App.ApplicationAdapter.create()
