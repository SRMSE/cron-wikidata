# Ask Serialization

Library containing serializers and deserializers for the PHP implementation of the
[Ask query language](https://github.com/wmde/Ask).

[![Build Status](https://secure.travis-ci.org/wmde/AskSerialization.png?branch=master)](http://travis-ci.org/wmde/AskSerialization)
[![Code Coverage](https://scrutinizer-ci.com/g/wmde/AskSerialization/badges/coverage.png?s=451c8ee074823e1a9cdb6ce2e891f6f9979519d1)](https://scrutinizer-ci.com/g/wmde/AskSerialization/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/wmde/AskSerialization/badges/quality-score.png?s=a5a22cfee53fc9b9bcb9fd7c27c7489fb2fbb614)](https://scrutinizer-ci.com/g/wmde/AskSerialization/)
[![Dependency Status](https://www.versioneye.com/php/ask:serialization/dev-master/badge.png)](https://www.versioneye.com/php/ask:serialization)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/ask/serialization/version.png)](https://packagist.org/packages/ask/serialization)
[![Download count](https://poser.pugx.org/ask/serialization/d/total.png)](https://packagist.org/packages/ask/serialization)

## Installation

You can use [Composer](http://getcomposer.org/) to download and install
this package as well as its dependencies. Alternatively you can simply clone
the git repository and take care of loading yourself.

To add this package as a local, per-project dependency to your project, simply add a
dependency on `ask/serialization` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
Ask 1.0:

    {
        "require": {
            "ask/serialization": "1.0.*"
        }
    }

## Library structure

The Ask language objects can all be serialized to a generic format from which the objects can later
be reconstructed. This is done via a set of `Serializers/Serializer` implementing objects. These
objects turn for instance a Query object into a data structure containing only primitive types and
arrays. This data structure can thus be readily fed to json_encode, serialize, or the like. The
process of reconstructing the objects from such a serialization is provided by objects implementing
the `Deserializers/Deserializer` interface.

Serializers can be obtained via an instance of `SerializerFactory` and deserializers can be obtained
via an instance of `DeserializerFactory`. You are not allowed to construct these serializers and
deserializers directly yourself or to have any kind of knowledge of them (ie type hinting). These
objects are internal to the Ask library and might change name or structure at any time. All you
are allowed to know when calling `$serializerFactory->newQuerySerializer()` is that you get back
an instance of `Serializers\Serializer`.

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

Ask has been written by [Jeroen De Dauw](https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw)
as [Wikimedia Germany](https://wikimedia.de) employee for the [Wikidata project](https://wikidata.org/).

## Release notes

### 1.0.3 (2014-11-25)

Installation together with DataValues 1.x is now supported.

### 1.0.2 (2014-07-24)

* When loaded with MediaWiki, this library now shows up under "other" on Special:Version
* Switched class loading from PSR-0 to PSR-4

### 1.0.1 (2014-04-02)

* Switched to using version 3.x of Serialization and version 1.x of DataValues Serialization.

### 1.0 (2013-12-05)

Initial release with these features:

* Serializers for all implemented Ask language objects
* Deserializers for all implemented Ask language objects

## Links

* [AskSerialization on Packagist](https://packagist.org/packages/ask/serialization)
* [AskSerialization on Ohloh](https://www.ohloh.net/p/ask)
* [AskSerialization on ScrutinizerCI](https://scrutinizer-ci.com/g/wmde/AskSerialization/)
* [TravisCI build status](https://travis-ci.org/wmde/AskSerialization)
* [Ask library](https://github.com/wmde/Ask)
* [NodeJS implementation of Ask](https://github.com/JeroenDeDauw/AskJS)
