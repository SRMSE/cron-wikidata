# DataValues Time

Library containing value objects to represent temporal information, parsers to turn user input
into such value objects, and formatters to turn them back into user consumable representations.

It is part of the [DataValues set of libraries](https://github.com/DataValues).

[![Build Status](https://secure.travis-ci.org/DataValues/Time.png?branch=master)](http://travis-ci.org/DataValues/Time)
[![Code Coverage](https://scrutinizer-ci.com/g/DataValues/Time/badges/coverage.png?s=c5db7b37576dedaedd28d27a0e5fda2b79e86da6)](https://scrutinizer-ci.com/g/DataValues/Time/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/DataValues/Time/badges/quality-score.png?s=3c66db1e59a8bf77f9e9a08760a92ca9c26538b9)](https://scrutinizer-ci.com/g/DataValues/Time/)
[![Dependency Status](https://www.versioneye.com/php/data-values:time/badge.png)](https://www.versioneye.com/php/data-values:time)

On [Packagist](https://packagist.org/packages/data-values/time):
[![Latest Stable Version](https://poser.pugx.org/data-values/time/version.png)](https://packagist.org/packages/data-values/time)
[![Download count](https://poser.pugx.org/data-values/time/d/total.png)](https://packagist.org/packages/data-values/time)

## Installation

The recommended way to use this library is via [Composer](http://getcomposer.org/).

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `data-values/time` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
version 1.0 of this package:

```js
    {
        "require": {
            "data-values/time": "1.0.*"
        }
    }
```

### Manual

Get the code of this package, either via git, or some other means. Also get all dependencies.
You can find a list of the dependencies in the "require" section of the composer.json file.
Then take care of autoloading the classes defined in the src directory.

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

DataValues Time has been written by the Wikidata team, as [Wikimedia Germany]
(https://wikimedia.de) employees for the [Wikidata project](https://wikidata.org/).

## Release notes

### 0.8.2 (2015-09-15)

* Fixed `IsoTimestampParser` and `TimeValue` accepting days with no month.
* Fixed `YearMonthDayTimeParser` rejecting YDM dates.
* `YearMonthDayTimeParser` accepts some more dates where month and day are the same anyway.

### 0.8.1 (2015-08-14)

#### Additions
* Added `YearMonthDayTimeParser`.
* `PhpDateTimeParser` now accepts space-separated dates in YMD order.

#### Other changes
* The component can now be installed together with DataValues Interfaces 0.2.x.
* The component can now be installed together with DataValues Common 0.3.x.

### 0.8.0 (2015-06-26)

#### Breaking changes
* `IsoTimestampParser` auto-detects the calendar model and does not default to Gregorian any more
* Removed `IsoTimestampParser::PRECISION_NONE`, use `null` instead
* `TimeValue`s leap second range changed from [0..62] to [0..61]

#### Additions
* Added `EraParser`
* Added `TimeValue::CALENDAR_GREGORIAN` and `TimeValue::CALENDAR_JULIAN`
* Renamed all `TimeValue::PRECISION_...` constants with lower case letters, e.g. `PRECISION_10a` to
  `PRECISION_YEAR10`, leaving backwards compatible aliases
* `IsoTimestampParser` now accepts time values with optional colons, per ISO
* `PhpDateTimeParser` now accepts comma separated dates

#### Other changes
* Fixed `IsoTimestampParser` not being able to set precision to hour, minute or second on midnight
* Deprecated `IsoTimestampParser::CALENDAR_GREGORIAN` and `IsoTimestampParser::CALENDAR_JULIAN`
* Deprecated `TimeFormatter::CALENDAR_GREGORIAN` and `TimeFormatter::CALENDAR_JULIAN`

### 0.7.0 (2015-04-20)

#### Breaking changes
* Renamed `TimeParser` to `IsoTimestampParser`
* Empty strings are now detected as invalid calendar models in the `TimeValue` constructor

#### Additions
* Added `MonthNameUnlocalizer`
* Added `PhpDateTimeParser`
* `IsoTimestampParser` can now parse various YMD ordered timestamp strings resembling ISO 8601
* `CalendarModelParser` now accepts URIs and localized calendar names given via options

#### Other changes
* The year in `TimeValue`s is now padded to 4 digits, and additional leading zeros are trimmed
* Major update of the `TimeValue` documentation
* Constructor arguments in `IsoTimestampParser` and `TimeFormatter` are optional now
* Fixed `TimeFormatter` delegating to an ISO timestamp formatter given via option
* `TimeFormatter` does not output the calendar model any more

### 0.6.1 (2014-10-09)

* Made component installable with DataValues 1.x

### 0.6.0 (2014-06-05)

* Added TimeValueCalculator
* Removed TimeIsoFormatter interface
* Introduced FORMAT_NAME class constants on ValueParsers in order to use them as expectedFormat
* Changed ValueParsers to pass rawValue and expectedFormat arguments when constructing a ParseException

### 0.5.2 (2014-04-28)

* Fix parsing of years ending in zero, defaulting precision to year when
  year is <= 4000 and >= 4000 BC.

### 0.5.1 (2014-03-24)

* Fix composer version of DataValues/Common

### 0.5.0 (2014-03-21)

* Removed TimeParser::SIGN_PATTERN constant
* Removed TimeParser::TIME_PATTERN constant
* Fixed [bug 62730](https://bugzilla.wikimedia.org/show_bug.cgi?id=62730). The TimeParser now returns the correct precision when only month and year or year is given

### 0.4.0 (2014-03-14)

* Corrected spelling errors calender/calander -> calendar

### 0.3.0 (2014-03-13)

* Renamed CalenderModelParser to CalendarModelParser
* Added Calandar and Precision options to TimeParser

### 0.2.0 (2014-02-11)

Added features:

* TimeParser
* CalenderModelParser

### 0.1.0 (2013-11-17)

Initial release with these features:

* TimeValue
* TimeFormatter
* TimeIsoFormatter

## Links

* [DataValues Time on Packagist](https://packagist.org/packages/data-values/time)
* [DataValues Time on TravisCI](https://travis-ci.org/DataValues/Time)
