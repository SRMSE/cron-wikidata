<?php

namespace Wikibase\DataModel\Services\Tests\Lookup;

use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\EntityRetrievingDataTypeLookup;
use Wikibase\DataModel\Services\Lookup\InMemoryEntityLookup;

/**
 * @covers Wikibase\DataModel\Services\Lookup\EntityRetrievingDataTypeLookup
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityRetrievingDataTypeLookupTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var string[]
	 */
	private $propertiesAndTypes = array(
		'P1' => 'NyanData all the way across the sky',
		'P42' => 'string',
		'P1337' => 'percentage',
		'P9001' => 'positive whole number',
	);

	/**
	 * @return EntityLookup
	 */
	private function newEntityLookup() {
		$lookup = new InMemoryEntityLookup();

		foreach ( $this->propertiesAndTypes as $propertyId => $dataTypeId ) {
			$property = Property::newFromType( $dataTypeId );
			$property->setId( new PropertyId( $propertyId ) );

			$lookup->addEntity( $property );
		}

		return $lookup;
	}

	public function getDataTypeForPropertyProvider() {
		$argLists = array();

		foreach ( $this->propertiesAndTypes as $propertyId => $dataTypeId ) {
			$argLists[] = array(
				new PropertyId( $propertyId ),
				$dataTypeId
			);
		}

		return $argLists;
	}

	/**
	 * @dataProvider getDataTypeForPropertyProvider
	 *
	 * @param PropertyId $propertyId
	 * @param string $expectedDataType
	 */
	public function testGetDataTypeForProperty( PropertyId $propertyId, $expectedDataType ) {
		$lookup = new EntityRetrievingDataTypeLookup( $this->newEntityLookup() );

		$actualDataType = $lookup->getDataTypeIdForProperty( $propertyId );

		$this->assertSame( $expectedDataType, $actualDataType );
	}

	// TODO: tests for not found

}
