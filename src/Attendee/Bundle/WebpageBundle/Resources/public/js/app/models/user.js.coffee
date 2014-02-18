App.User = DS.Model.extend
  firstName: DS.attr 'string'
  lastName:  DS.attr 'string'
  email:     DS.attr 'string'

  name: ( ->
    if @get('firstName') and @get('lastName')
      @get('firstName') + ' ' + @get('lastName')
    else
      @get('email')
  ).property('firstName', 'lastName')
