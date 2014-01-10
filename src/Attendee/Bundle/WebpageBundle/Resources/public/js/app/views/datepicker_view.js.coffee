App.DatePickerField = Em.TextField.extend
  tagName: 'input',
  type: 'datetime-local',
  attributeBindings: ['type', 'value'],
