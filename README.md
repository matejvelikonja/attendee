# Attendee

[![Build Status](https://travis-ci.org/matejvelikonja/attendee.png?branch=master)](https://travis-ci.org/matejvelikonja/attendee)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/74b322c6-707e-40ff-a390-7ede494c4c55/mini.png)](https://insight.sensiolabs.com/projects/74b322c6-707e-40ff-a390-7ede494c4c55)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/matejvelikonja/attendee/badges/quality-score.png?s=c9c14ac68c0abcaf36164a6884b19e5f4eaed882)](https://scrutinizer-ci.com/g/matejvelikonja/attendee/)
[![Code Coverage](https://scrutinizer-ci.com/g/matejvelikonja/attendee/badges/coverage.png?s=725e5c1ed511243ac7f1fd1aec5f46fe6d917c77)](https://scrutinizer-ci.com/g/matejvelikonja/attendee/)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/matejvelikonja/attendee/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

## Console

### List of commands

#### Schedule create command

Example:
```bash
$ app/console attendee:schedule:create \
--teams="Team name" \
--name="Every thursday at 18h for 1 month and duration of 2 hours and 15 minutes" \
--startsAt="now" \
--endsAt="+1 month" \
--rRule="FREQ=WEEKLY;BYDAY=TH;BYHOUR=18" \
--duration="2 hours 15 minutes"
```

#### Make user a team manager command

Example:
```bash
$ app/console attendee:team:add-manager --team 7 --email admin@example.com
```

## Development

# Tests

```bash
$ phpunit
```
