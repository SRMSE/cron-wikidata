<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'WikidataQueryApi\\' => array($vendorDir . '/ppp/wikidataquery-api/src'),
    'Wikibase\\EntityStore\\' => array($baseDir . '/src'),
    'Wikibase\\DataModel\\Services\\' => array($vendorDir . '/wikibase/data-model-services/src'),
    'Wikibase\\DataModel\\' => array($vendorDir . '/wikibase/data-model/src', $vendorDir . '/wikibase/data-model-serialization/src'),
    'ValueValidators\\' => array($vendorDir . '/data-values/interfaces/src/ValueValidators', $vendorDir . '/data-values/validators/src'),
    'ValueParsers\\' => array($vendorDir . '/data-values/interfaces/src/ValueParsers', $vendorDir . '/data-values/common/src/ValueParsers'),
    'ValueFormatters\\' => array($vendorDir . '/data-values/interfaces/src/ValueFormatters', $vendorDir . '/data-values/common/src/ValueFormatters'),
    'Symfony\\Polyfill\\Mbstring\\' => array($vendorDir . '/symfony/polyfill-mbstring'),
    'Symfony\\Component\\Filesystem\\' => array($vendorDir . '/symfony/filesystem'),
    'Symfony\\Component\\Console\\' => array($vendorDir . '/symfony/console'),
    'Symfony\\Component\\Config\\' => array($vendorDir . '/symfony/config'),
    'Serializers\\' => array($vendorDir . '/serialization/serialization/src/Serializers'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Mediawiki\\Api\\' => array($vendorDir . '/addwiki/mediawiki-api-base/src'),
    'GuzzleHttp\\Psr7\\' => array($vendorDir . '/guzzlehttp/psr7/src'),
    'GuzzleHttp\\Promise\\' => array($vendorDir . '/guzzlehttp/promises/src'),
    'GuzzleHttp\\' => array($vendorDir . '/guzzlehttp/guzzle/src'),
    'Doctrine\\Common\\Cache\\' => array($vendorDir . '/doctrine/cache/lib/Doctrine/Common/Cache'),
    'Doctrine\\Common\\' => array($vendorDir . '/doctrine/common/lib/Doctrine/Common'),
    'Diff\\' => array($vendorDir . '/diff/diff/src'),
    'Deserializers\\' => array($vendorDir . '/serialization/serialization/src/Deserializers'),
    'DataValues\\Serializers\\' => array($vendorDir . '/data-values/serialization/src/Serializers'),
    'DataValues\\Geo\\' => array($vendorDir . '/data-values/geo/src'),
    'DataValues\\Deserializers\\' => array($vendorDir . '/data-values/serialization/src/Deserializers'),
    'DataValues\\' => array($vendorDir . '/data-values/common/src/DataValues'),
    'Ask\\Serializers\\' => array($vendorDir . '/ask/serialization/src/Serializers'),
    'Ask\\Deserializers\\' => array($vendorDir . '/ask/serialization/src/Deserializers'),
    'Ask\\' => array($vendorDir . '/ask/ask/src', $vendorDir . '/ask/serialization/src'),
);
