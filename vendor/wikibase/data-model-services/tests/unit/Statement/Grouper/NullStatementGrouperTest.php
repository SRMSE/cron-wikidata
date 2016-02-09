<?php

namespace Wikibase\DataModel\Services\Tests\Statement\Grouper;

use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Services\Statement\Grouper\NullStatementGrouper;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\DataModel\Statement\StatementList;

/**
 * @covers Wikibase\DataModel\Services\Statement\Grouper\NullStatementGrouper
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class NullStatementGrouperTest extends PHPUnit_Framework_TestCase {

	public function testGroupStatements() {
		$statements = new StatementList();
		$statements->addNewStatement( new PropertyNoValueSnak( 1 ) );

		$expected = array( 'statements' => $statements );

		$grouper = new NullStatementGrouper();
		$this->assertSame( $expected, $grouper->groupStatements( $statements ) );
	}

}
