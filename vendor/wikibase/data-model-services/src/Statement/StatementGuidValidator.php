<?php

namespace Wikibase\DataModel\Services\Statement;

use Wikibase\DataModel\Entity\EntityIdParser;
use Wikibase\DataModel\Entity\EntityIdParsingException;
use Wikibase\DataModel\Statement\StatementGuid;

/**
 * @since 1.1
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class StatementGuidValidator {

	/**
	 * @var EntityIdParser
	 */
	protected $entityIdParser;

	/**
	 * @param EntityIdParser $entityIdParser
	 */
	public function __construct( EntityIdParser $entityIdParser ) {
		$this->entityIdParser = $entityIdParser;
	}

	/**
	 * Validates a statement guid
	 *
	 * @since 0.4
	 *
	 * @param string $guid
	 *
	 * @return boolean
	 */
	public function validate( $guid ) {
		if ( ! $this->validateFormat( $guid ) ) {
			return false;
		}

		$guidParts = explode( StatementGuid::SEPARATOR, $guid );

		if ( ! $this->validateStatementGuidPrefix( $guidParts[0] ) || ! $this->validateGuid( $guidParts[1] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Basic validation for statement guid format
	 *
	 * @since 0.4
	 *
	 * @param string $guid
	 *
	 * @return boolean
	 */
	public function validateFormat( $guid ) {
		if ( ! is_string( $guid ) ) {
			return false;
		}

		$keyParts = explode( '$', $guid );

		if ( count( $keyParts ) !== 2 ) {
			return false;
		}

		return true;
	}

	/**
	 * Validate the second part of a statement guid, after the $
	 *
	 * @since 0.4
	 *
	 * @param string $guid
	 *
	 * @return boolean
	 */
	protected function validateGuid( $guid ) {
		$guidFormat = '/^\{?[A-Z\d]{8}-[A-Z\d]{4}-[A-Z\d]{4}-[A-Z\d]{4}-[A-Z\d]{12}\}?$/i';

		if ( !preg_match( $guidFormat, $guid ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Validate the statement guid prefix is a valid entity id
	 *
	 * @since 0.4
	 *
	 * @param string $prefixedId
	 *
	 * @return boolean
	 */
	protected function validateStatementGuidPrefix( $prefixedId ) {
		try {
			$this->entityIdParser->parse( $prefixedId );
			return true;
		} catch ( EntityIdParsingException $ex ) {
			return false;
		}
	}

}
