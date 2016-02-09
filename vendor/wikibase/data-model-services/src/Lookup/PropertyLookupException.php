<?php

namespace Wikibase\DataModel\Services\Lookup;

use Exception;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @since 2.0
 *
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class PropertyLookupException extends EntityLookupException {

	/**
	 * @param PropertyId $propertyId
	 * @param string|null $message
	 * @param Exception|null $previous
	 */
	public function __construct( PropertyId $propertyId, $message = null, Exception $previous = null ) {
		parent::__construct(
			$propertyId,
			$message ?: 'Property lookup failed for: ' . $propertyId,
			$previous
		);
	}

	/**
	 * @return PropertyId
	 */
	public function getPropertyId() {
		return $this->getEntityId();
	}

}
