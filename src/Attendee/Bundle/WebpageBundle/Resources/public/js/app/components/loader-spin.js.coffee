App.LoaderSpinComponent = Ember.Component.extend
  tagName: 'div'
  options =
    lines:     17        # The number of lines to draw
    length:    40        # The length of each line
    width:     30        # The line thickness
    radius:    24        # The radius of the inner circle
    corners:   1         # Corner roundness (0..1)
    rotate:    59        # The rotation offset
    direction: 1         # 1: clockwise, -1: counterclockwise
    color:     "#000"    # rgb or #rrggbb or array of colors
    speed:     1         # Rounds per second
    trail:     83        # Afterglow percentage
    shadow:    false     # Whether to render a shadow
    hwaccel:   true      # Whether to use hardware acceleration
    className: "spinner" # The CSS class to assign to the spinner
    zIndex:    2e9       # The z-index (defaults to 2000000000)
    top:       0         # Top position relative to parent in px
    left:      0         # Left position relative to parent in px

  start: (->
    spinner = new Spinner(options).spin()
    this.$().append(spinner.el)
  ).on('didInsertElement')