<?php

namespace Wikibase\DataModel\Tests\Entity;

use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\ItemIdSet;

/**
 * @covers Wikibase\DataModel\Entity\ItemIdSet
 *
 * @group Wikibase
 * @group WikibaseDataModel
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemIdSetTest extends \PHPUnit_Framework_TestCase {

	public function testGetIterator() {
		$set = new ItemIdSet();
		$this->assertInstanceOf( 'Traversable', $set->getIterator() );
	}

	/**
	 * @dataProvider serializationsProvider
	 */
	public function testGetSerializations( array $itemIds, array $expected ) {
		$set = new ItemIdSet( $itemIds );
		$this->assertEquals( $expected, $set->getSerializations() );
	}

	public function serializationsProvider() {
		return array(
			array( array(), array() ),
			array( array( new ItemId( 'Q1' ) ), array( 'Q1' ) ),
			array( array( new ItemId( 'Q1' ), new ItemId( 'Q2' ) ), array( 'Q1', 'Q2' ) ),
		);
	}

	public function testGivenEmptySet_countReturnsZero() {
		$set = new ItemIdSet();
		$this->assertEquals( 0, $set->count() );
	}

	public function testGivenSetWithTwoItems_countReturnsTwo() {
		$set = new ItemIdSet( array(
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
		) );
		$this->assertEquals( 2, $set->count() );
	}

	public function testGivenNotSetId_hasReturnsFalse() {
		$set = new ItemIdSet( array( new ItemId( 'Q1337' ) ) );
		$this->assertFalse( $set->has( new ItemId( 'Q1' ) ) );
	}

	public function testGivenSetId_hasReturnsTrue() {
		$set = new ItemIdSet( array( new ItemId( 'Q1337' ) ) );
		$this->assertTrue( $set->has( new ItemId( 'Q1337' ) ) );
	}

	public function testCanIterateOverSet() {
		$array = array(
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
		);

		$set = new ItemIdSet( $array );
		$this->assertEquals( $array, array_values( iterator_to_array( $set ) ) );
	}

	public function testGivenDuplicates_noLongerPresentInIteration() {
		$array = array(
			new ItemId( 'Q1' ),
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
		);

		$set = new ItemIdSet( $array );

		$this->assertEquals(
			array(
				new ItemId( 'Q1' ),
				new ItemId( 'Q2' ),
			),
			array_values( iterator_to_array( $set ) )
		);
	}

	/**
	 * @dataProvider itemIdSetProvider
	 */
	public function testGivenTheSameSet_equalsReturnsTrue( ItemIdSet $set ) {
		$this->assertTrue( $set->equals( $set ) );
		$this->assertTrue( $set->equals( clone $set ) );
	}

	public function itemIdSetProvider() {
		return array(
			array(
				new ItemIdSet(),
			),

			array(
				new ItemIdSet( array(
					new ItemId( 'Q1' ),
				) ),
			),

			array(
				new ItemIdSet( array(
					new ItemId( 'Q1' ),
					new ItemId( 'Q2' ),
					new ItemId( 'Q3' ),
				) ),
			),
		);
	}

	public function testGivenNonItemIdSet_equalsReturnsFalse() {
		$set = new ItemIdSet();
		$this->assertFalse( $set->equals( null ) );
		$this->assertFalse( $set->equals( new \stdClass() ) );
	}

	public function testGivenDifferentSet_equalsReturnsFalse() {
		$setOne = new ItemIdSet( array(
			new ItemId( 'Q1337' ),
			new ItemId( 'Q1' ),
			new ItemId( 'Q42' ),
		) );

		$setTwo = new ItemIdSet( array(
			new ItemId( 'Q1337' ),
			new ItemId( 'Q2' ),
			new ItemId( 'Q42' ),
		) );

		$this->assertFalse( $setOne->equals( $setTwo ) );
	}

	public function testGivenSetWithDifferentOrder_equalsReturnsTrue() {
		$setOne = new ItemIdSet( array(
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
			new ItemId( 'Q3' ),
		) );

		$setTwo = new ItemIdSet( array(
			new ItemId( 'Q2' ),
			new ItemId( 'Q3' ),
			new ItemId( 'Q1' ),
		) );

		$this->assertTrue( $setOne->equals( $setTwo ) );
	}

	public function testGivenDifferentSizedSets_equalsReturnsFalse() {
		$small = new ItemIdSet( array(
			new ItemId( 'Q1' ),
		) );

		$big = new ItemIdSet( array(
			new ItemId( 'Q1' ),
			new ItemId( 'Q2' ),
			new ItemId( 'Q3' ),
		) );

		$this->assertFalse( $small->equals( $big ) );
		$this->assertFalse( $big->equals( $small ) );
	}

}
