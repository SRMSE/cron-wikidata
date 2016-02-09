<?php

namespace Wikibase\DataModel\Services\Tests\Diff;

use Diff\DiffOp\Diff\Diff;
use Diff\DiffOp\DiffOpAdd;
use Diff\DiffOp\DiffOpChange;
use Diff\DiffOp\DiffOpRemove;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Services\Diff\ItemDiffer;
use Wikibase\DataModel\Snak\PropertySomeValueSnak;

/**
 * @covers Wikibase\DataModel\Services\Diff\ItemDiffer
 * @covers Wikibase\DataModel\Services\Diff\ItemDiff
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemDifferTest extends \PHPUnit_Framework_TestCase {

	public function testGivenTwoEmptyItems_emptyItemDiffIsReturned() {
		$differ = new ItemDiffer();

		$diff = $differ->diffEntities( new Item(), new Item() );

		$this->assertInstanceOf( 'Wikibase\DataModel\Services\Diff\ItemDiff', $diff );
		$this->assertTrue( $diff->isEmpty() );
	}

	public function testFingerprintIsDiffed() {
		$firstItem = new Item();
		$firstItem->getFingerprint()->setLabel( 'en', 'kittens' );
		$firstItem->getFingerprint()->setAliasGroup( 'en', array( 'cats' ) );

		$secondItem = new Item();
		$secondItem->getFingerprint()->setLabel( 'en', 'nyan' );
		$secondItem->getFingerprint()->setDescription( 'en', 'foo bar baz' );

		$differ = new ItemDiffer();
		$diff = $differ->diffItems( $firstItem, $secondItem );

		$this->assertEquals(
			new Diff( array( 'en' => new DiffOpChange( 'kittens', 'nyan' ) ) ),
			$diff->getLabelsDiff()
		);

		$this->assertEquals(
			new Diff( array( 'en' => new DiffOpAdd( 'foo bar baz' ) ) ),
			$diff->getDescriptionsDiff()
		);

		$this->assertEquals(
			new Diff( array( 'en' => new Diff( array( new DiffOpRemove( 'cats' ) ) ) ) ),
			$diff->getAliasesDiff()
		);
	}

	public function testClaimsAreDiffed() {
		$firstItem = new Item();

		$secondItem = new Item();
		$secondItem->getStatements()->addNewStatement( new PropertySomeValueSnak( 42 ), null, null, 'guid' );

		$differ = new ItemDiffer();
		$diff = $differ->diffItems( $firstItem, $secondItem );

		$this->assertCount( 1, $diff->getClaimsDiff()->getAdditions() );
	}

	public function testGivenEmptyItem_constructionDiffIsEmpty() {
		$differ = new ItemDiffer();
		$this->assertTrue( $differ->getConstructionDiff( new Item() )->isEmpty() );
	}

	public function testGivenEmptyItem_destructionDiffIsEmpty() {
		$differ = new ItemDiffer();
		$this->assertTrue( $differ->getDestructionDiff( new Item() )->isEmpty() );
	}

	public function testConstructionDiffContainsAddOperations() {
		$item = new Item();
		$item->getFingerprint()->setLabel( 'en', 'foo' );
		$item->getSiteLinkList()->addNewSiteLink( 'bar', 'baz' );

		$differ = new ItemDiffer();
		$diff = $differ->getConstructionDiff( $item );

		$this->assertEquals(
			new Diff( array( 'en' => new DiffOpAdd( 'foo' ) ) ),
			$diff->getLabelsDiff()
		);

		$this->assertEquals(
			new Diff( array( 'bar' => new Diff( array( 'name' => new DiffOpAdd( 'baz' ) ) ) ) ),
			$diff->getSiteLinkDiff()
		);
	}

}
