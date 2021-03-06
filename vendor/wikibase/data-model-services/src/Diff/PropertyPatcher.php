<?php

namespace Wikibase\DataModel\Services\Diff;

use InvalidArgumentException;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Services\Diff\Internal\FingerprintPatcher;
use Wikibase\DataModel\Services\Diff\Internal\StatementListPatcher;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyPatcher implements EntityPatcherStrategy {

	/**
	 * @var FingerprintPatcher
	 */
	private $fingerprintPatcher;

	/**
	 * @var StatementListPatcher
	 */
	private $statementListPatcher;

	public function __construct() {
		$this->fingerprintPatcher = new FingerprintPatcher();
		$this->statementListPatcher = new StatementListPatcher();
	}

	/**
	 * @param string $entityType
	 *
	 * @return boolean
	 */
	public function canPatchEntityType( $entityType ) {
		return $entityType === 'property';
	}

	/**
	 * @param EntityDocument $entity
	 * @param EntityDiff $patch
	 *
	 * @return Property
	 * @throws InvalidArgumentException
	 */
	public function patchEntity( EntityDocument $entity, EntityDiff $patch ) {
		$this->assertIsProperty( $entity );

		$this->patchProperty( $entity, $patch );
	}

	private function assertIsProperty( EntityDocument $property ) {
		if ( !( $property instanceof Property ) ) {
			throw new InvalidArgumentException( '$property must be an instance of Property' );
		}
	}

	private function patchProperty( Property $property, EntityDiff $patch ) {
		$this->fingerprintPatcher->patchFingerprint( $property->getFingerprint(), $patch );

		$property->setStatements( $this->statementListPatcher->getPatchedStatementList(
			$property->getStatements(),
			$patch->getClaimsDiff()
		) );
	}

}
