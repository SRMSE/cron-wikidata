<?php

namespace Wikibase\DataModel\Services\Tests;

use ArrayObject;
use OutOfBoundsException;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\PropertyIdProvider;
use Wikibase\DataModel\Services\ByPropertyIdGrouper;
use Wikibase\DataModel\Snak\Snak;

/**
 * @covers Wikibase\DataModel\Services\ByPropertyIdGrouper
 *
 * @license GNU GPL v2+
 * @author Bene* < benestar.wikimedia@gmail.com >
 * @author Thiemo Mättig
 */
class ByPropertyIdGrouperTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider validConstructorArgumentProvider
	 */
	public function testConstructor( $argument ) {
		$instance = new ByPropertyIdGrouper( $argument );
		$this->assertCount( count( $argument ), $instance->getPropertyIds() );
	}

	public function validConstructorArgumentProvider() {
		return array(
			array( array() ),
			array( array( $this->getPropertyIdProviderMock( 'P1' ) ) ),
			array( new ArrayObject() ),
			array( new ArrayObject( array( $this->getPropertyIdProviderMock( 'P1' ) ) ) ),
		);
	}

	/**
	 * @dataProvider invalidConstructorArgumentProvider
	 */
	public function testConstructorThrowsException( $argument ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new ByPropertyIdGrouper( $argument );
	}

	public function invalidConstructorArgumentProvider() {
		return array(
			array( null ),
			array( 'notAnObject' ),
			array( array( null ) ),
			array( array( 'notAnObject' ) ),
			array( new ArrayObject( array( null ) ) ),
			array( new ArrayObject( array( 'notAnObject' ) ) ),
		);
	}

	public function provideGetPropertyIds() {
		$cases = array();

		$cases['empty list'] = array(
			array(),
			array()
		);

		$cases['some property ids'] = array(
			array(
				$this->getPropertyIdProviderMock( 'P42' ),
				$this->getPropertyIdProviderMock( 'P23' )
			),
			array(
				new PropertyId( 'P42' ),
				new PropertyId( 'P23' )
			)
		);

		$cases['duplicate property ids'] = array(
			$this->getPropertyIdProviders(),
			array(
				new PropertyId( 'P42' ),
				new PropertyId( 'P23' ),
				new PropertyId( 'P15' ),
				new PropertyId( 'P10' )
			)
		);

		return $cases;
	}

	/**
	 * @dataProvider provideGetPropertyIds
	 * @param PropertyIdProvider[] $propertyIdProviders
	 * @param PropertyId[] $expectedPropertyIds
	 */
	public function testGetPropertyIds( array $propertyIdProviders, array $expectedPropertyIds ) {
		$byPropertyIdGrouper = new ByPropertyIdGrouper( $propertyIdProviders );
		$propertyIds = $byPropertyIdGrouper->getPropertyIds();
		$this->assertEquals( $expectedPropertyIds, $propertyIds );
	}

	public function provideGetByPropertyId() {
		$cases = array();

		$cases[] = array(
			$this->getPropertyIdProviders(),
			'P42',
			array( 'abc', 'jkl' )
		);

		$cases[] = array(
			$this->getPropertyIdProviders(),
			'P23',
			array( 'def' )
		);

		return $cases;
	}

	/**
	 * @dataProvider provideGetByPropertyId
	 */
	public function testGetByPropertyId( array $propertyIdProviders, $propertyId, array $expectedValues ) {
		$byPropertyIdGrouper = new ByPropertyIdGrouper( $propertyIdProviders );
		$values = $byPropertyIdGrouper->getByPropertyId( new PropertyId( $propertyId ) );
		array_walk( $values, function( Snak &$value ) {
			$value = $value->getType();
		} );
		$this->assertEquals( $expectedValues, $values );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetByPropertyIdThrowsException() {
		$byPropertyIdGrouper = new ByPropertyIdGrouper( $this->getPropertyIdProviders() );
		$byPropertyIdGrouper->getByPropertyId( new PropertyId( 'P11' ) );
	}

	public function provideHasPropertyId() {
		$cases = array();

		$cases[] = array( $this->getPropertyIdProviders(), 'P42', true );
		$cases[] = array( $this->getPropertyIdProviders(), 'P23', true );
		$cases[] = array( $this->getPropertyIdProviders(), 'P15', true );
		$cases[] = array( $this->getPropertyIdProviders(), 'P10', true );
		$cases[] = array( $this->getPropertyIdProviders(), 'P11', false );

		return $cases;
	}

	/**
	 * @dataProvider provideHasPropertyId
	 */
	public function testHasPropertyId( array $propertyIdProviders, $propertyId, $expectedValue ) {
		$byPropertyIdGrouper = new ByPropertyIdGrouper( $propertyIdProviders );
		$this->assertEquals( $expectedValue, $byPropertyIdGrouper->hasPropertyId( new PropertyId( $propertyId ) ) );
	}

	/**
	 * @return PropertyIdProvider[]
	 */
	private function getPropertyIdProviders() {
		return array(
			$this->getPropertyIdProviderMock( 'P42', 'abc' ),
			$this->getPropertyIdProviderMock( 'P23', 'def' ),
			$this->getPropertyIdProviderMock( 'P15', 'ghi' ),
			$this->getPropertyIdProviderMock( 'P42', 'jkl' ),
			$this->getPropertyIdProviderMock( 'P10', 'mno' )
		);
	}

	/**
	 * Creates a PropertyIdProvider mock which can return a value.
	 *
	 * @param string $propertyId
	 * @param string|null $type
	 *
	 * @return PropertyIdProvider
	 */
	private function getPropertyIdProviderMock( $propertyId, $type = null ) {
		$propertyIdProvider = $this->getMock( 'Wikibase\DataModel\Snak\Snak' );

		$propertyIdProvider->expects( $this->once() )
			->method( 'getPropertyId' )
			->will( $this->returnValue( new PropertyId( $propertyId ) ) );

		$propertyIdProvider->expects( $this->any() )
			->method( 'getType' )
			->will( $this->returnValue( $type ) );

		return $propertyIdProvider;
	}

}
