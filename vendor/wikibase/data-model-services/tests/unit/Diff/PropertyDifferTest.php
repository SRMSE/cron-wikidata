<?php

namespace Wikibase\DataModel\Services\Tests\Diff;

use Wikibase\DataModel\Services\Diff\PropertyDiffer;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Snak\PropertySomeValueSnak;

/**
 * @covers Wikibase\DataModel\Services\Diff\PropertyDiffer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyDifferTest extends \PHPUnit_Framework_TestCase {

	public function testGivenPropertyWithOnlyType_constructionDiffIsEmpty() {
		$differ = new PropertyDiffer();
		$this->assertTrue( $differ->getConstructionDiff( Property::newFromType( 'string' ) )->isEmpty() );
	}

	public function testGivenPropertyWithOnlyType_destructionDiffIsEmpty() {
		$differ = new PropertyDiffer();
		$this->assertTrue( $differ->getDestructionDiff( Property::newFromType( 'string' ) )->isEmpty() );
	}

	public function testClaimsAreDiffed() {
		$firstProperty = Property::newFromType( 'kittens' );

		$secondProperty = Property::newFromType( 'kittens' );
		$secondProperty->getStatements()->addNewStatement( new PropertySomeValueSnak( 42 ), null, null, 'guid' );

		$differ = new PropertyDiffer();
		$diff = $differ->diffProperties( $firstProperty, $secondProperty );

		$this->assertCount( 1, $diff->getClaimsDiff()->getAdditions() );
	}

}
