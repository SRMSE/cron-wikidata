# DataValues Number

Library containing value objects to represent numeric information, parsers to turn user input
into such value objects, and formatters to turn them back into user consumable representations.

It is part of the [DataValues set of libraries](https://github.com/DataValues).

[![Build Status](https://secure.travis-ci.org/DataValues/Number.png?branch=master)](http://travis-ci.org/DataValues/Number)
[![Code Coverage](https://scrutinizer-ci.com/g/DataValues/Number/badges/coverage.png?s=a62dd85d05eaf0c5505deed4e2bd53d34e50d158)](https://scrutinizer-ci.com/g/DataValues/Number/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/DataValues/Number/badges/quality-score.png?s=03279530fa55439de3ce094b985f861959ee7162)](https://scrutinizer-ci.com/g/DataValues/Number/)
[![Dependency Status](https://www.versioneye.com/php/data-values:number/badge.png)](https://www.versioneye.com/php/data-values:number)

On [Packagist](https://packagist.org/packages/data-values/number):
[![Latest Stable Version](https://poser.pugx.org/data-values/number/version.png)](https://packagist.org/packages/data-values/number)
[![Download count](https://poser.pugx.org/data-values/number/d/total.png)](https://packagist.org/packages/data-values/number)

## Installation

The recommended way to use this library is via [Composer](http://getcomposer.org/).

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `data-values/number` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
version 1.0 of this package:

    {
        "require": {
            "data-values/number": "1.0.*"
        }
    }

### Manual

Get the code of this package, either via git, or some other means. Also get all dependencies.
You can find a list of the dependencies in the "require" section of the composer.json file.
Then take care of autoloading the classes defined in the src directory.

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

DataValues Number has been written by Daniel Kinzler, as [Wikimedia Germany]
(https://wikimedia.de) employee for the [Wikidata project](https://wikidata.org/).

## Release notes

### 0.6.0 (2015-09-09)

#### Breaking changes
* `QuantityFormatter` constructor parameters changed in an incompatible way.
* `BasicNumberUnlocalizer::getUnitRegex` returns an empty string. A `QuantityParser` using this
	does not accept units as part of the input any more.

#### Additions
* Added `QuantityHtmlFormatter`.
* `QuantityFormatter` supports an optional format string to concatenate number and unit.

#### Other changes
* Deprecated `QuantityUnitFormatter` interface.
* Deprecated `BasicQuantityUnitFormatter`.
* `QuantityParser` now always trims the unit it gets via option.
* The component can now be installed together with DataValues Interfaces 0.2.x.

### 0.5.0 (2015-06-11)

#### Breaking changes
* `QuantityFormatter` constructor parameters changed in an incompatible way

#### Additions
* Added `QuantityUnitFormatter` interface
* Added `BasicQuantityUnitFormatter`
* Added `QuantityFormatter::OPT_APPLY_UNIT` option
* Added `QuantityParser::OPT_UNIT` option
* Added `DecimalParser::applyDecimalExponent`
* Added `DecimalParser::splitDecimalExponent`

#### Other changes
* `QuantityParser` now correctly detects precision for scientific notation
* Made constructor parameters optional in `DecimalFormatter` and `QuantityFormatter`
* Updated DataValues Interfaces dependency to 0.1.5

### 0.4.1 (2014-10-09)

* The component can now be installed together with DataValues 1.x

### 0.4 (2014-04-24)

* Unlocalizer interface renamed to NumberUnlocalizer
* Localizer interface renamed to NumberLocalizer
* BasicUnlocalizer interface renamed to BasicNumberUnlocalizer
* BasicLocalizer interface renamed to BasicNumberLocalizer
* Introduced FORMAT_NAME class constants on ValueParsers in order to use them as
	expectedFormat
* Changed ValueParsers to pass rawValue and expectedFormat when constructing
	a ParseException

### 0.3 (2014-03-12)

* Unlocalizer: added getNumberRegex() and getUnitRegex()
* Unlocalizer: replaced unlocalize() with unlocalizeNumber()
* Localizer: replaced localize() with localizeNumber()
* Localizer and Unlocalizer: no longer require the target language and options in method calls
* QuantityParser: fixed parsing of internationalized quantity strings

### 0.2 (2013-12-16)

#### Removals

* IntParser got moved to data-values/common
* FloatParser got moved to data-values/common

#### Additions

* DecimalMath::min
* DecimalMath::max
* DecimalMath::shift
* Added option to force displaying the sign in DecimalFormatter

#### Improvements

* QuantityParser and DecimalParser now support scientific notation
* DecimalParser now supports localized parsing of values
* DecimalFormatter now supports localization of values

#### Bug fixes

* Floating point errors that occurred when manipulating decimal values have been fixed.
([bug 56682](https://bugzilla.wikimedia.org/show_bug.cgi?id=56682))

### 0.1 (2013-11-17)

Initial release with these features:

* DecimalMath
* DecimalValue
* QuantityValue
* DecimalFormatter
* QuantityFormatter
* DecimalParser
* FloatParser
* IntParser
* QuantityParser

## Links

* [DataValues Number on Packagist](https://packagist.org/packages/data-values/number)
* [DataValues Number on TravisCI](https://travis-ci.org/DataValues/Number)
