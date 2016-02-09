<?php

/**
 * Entry point of the Ask library.
 *
 * @since 1.0
 * @codeCoverageIgnore
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'Ask_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

define( 'Ask_VERSION', '1.0.2' );

// Attempt to include the dependencies if one has not been loaded yet.
// This is the path to the autoloader generated by composer in case of a composer install.
if ( !defined( 'DataValues_VERSION' ) && is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

if ( defined( 'MEDIAWIKI' ) ) {
	call_user_func( function() {
		require_once __DIR__ . '/Ask.mw.php';
	} );
}
