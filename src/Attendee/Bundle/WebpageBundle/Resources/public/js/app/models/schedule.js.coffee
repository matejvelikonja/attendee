App.Schedule = DS.Model.extend
  name:      DS.attr      'string'
  starts_at: DS.attr      'string'
  r_rule:    DS.attr      'string'
  team:      DS.belongsTo 'team'
