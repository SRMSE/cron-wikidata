<?php

namespace Wikibase\DataModel\Tests\Term;

use InvalidArgumentException;
use Wikibase\DataModel\Term\Term;
use Wikibase\DataModel\Term\TermList;

/**
 * @covers Wikibase\DataModel\Term\TermList
 * @uses Wikibase\DataModel\Term\Term
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TermListTest extends \PHPUnit_Framework_TestCase {

	public function testIsEmpty() {
		$list = new TermList();
		$this->assertTrue( $list->isEmpty() );

		$list = new TermList( array( new Term( 'en', 'foo' ) ) );
		$this->assertFalse( $list->isEmpty() );
	}

	public function testGivenNoTerms_sizeIsZero() {
		$list = new TermList();
		$this->assertCount( 0, $list );
	}

	public function testGivenTwoTerms_countReturnsTwo() {
		$list = new TermList( $this->getTwoTerms() );

		$this->assertCount( 2, $list );
	}

	private function getTwoTerms() {
		return array(
			'en' => new Term( 'en', 'foo' ),
			'de' => new Term( 'de', 'bar' ),
		);
	}

	public function testGivenTwoTerms_listContainsThem() {
		$array = $this->getTwoTerms();

		$list = new TermList( $array );

		$this->assertEquals( $array, iterator_to_array( $list ) );
	}

	public function testGivenTermsWithTheSameLanguage_onlyTheLastOnesAreRetained() {
		$array = array(
			new Term( 'en', 'foo' ),
			new Term( 'en', 'bar' ),

			new Term( 'de', 'baz' ),

			new Term( 'nl', 'bah' ),
			new Term( 'nl', 'blah' ),
			new Term( 'nl', 'spam' ),
		);

		$list = new TermList( $array );

		$this->assertEquals(
			array(
				'en' => new Term( 'en', 'bar' ),
				'de' => new Term( 'de', 'baz' ),
				'nl' => new Term( 'nl', 'spam' ),
			),
			iterator_to_array( $list )
		);
	}

	public function testGivenNoTerms_toTextArrayReturnsEmptyArray() {
		$list = new TermList();
		$this->assertEquals( array(), $list->toTextArray() );
	}

	public function testGivenTerms_toTextArrayReturnsTermsInFormat() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$this->assertEquals(
			array(
				'en' => 'foo',
				'de' => 'bar',
			),
			$list->toTextArray()
		);
	}

	public function testCanIterateOverList() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
		) );

		foreach ( $list as $key => $term ) {
			$this->assertEquals( 'en', $key );
			$this->assertEquals( new Term( 'en', 'foo' ), $term );
		}
	}

	public function testGivenSetLanguageCode_getByLanguageReturnsGroup() {
		$enTerm = new Term( 'en', 'a' );

		$list = new TermList( array(
			new Term( 'de', 'b' ),
			$enTerm,
			new Term( 'nl', 'c' ),
		) );

		$this->assertEquals( $enTerm, $list->getByLanguage( 'en' ) );
	}

	/**
	 * @dataProvider invalidLanguageCodeProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testGivenInvalidLanguageCode_getByLanguageThrowsException( $languageCode ) {
		$list = new TermList();
		$list->getByLanguage( $languageCode );
	}

	public function testGivenNonSetLanguageCode_getByLanguageThrowsException() {
		$list = new TermList();

		$this->setExpectedException( 'OutOfBoundsException' );
		$list->getByLanguage( 'en' );
	}

	public function testHasTermForLanguage() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$this->assertTrue( $list->hasTermForLanguage( 'en' ) );
		$this->assertTrue( $list->hasTermForLanguage( 'de' ) );

		$this->assertFalse( $list->hasTermForLanguage( 'nl' ) );
		$this->assertFalse( $list->hasTermForLanguage( 'fr' ) );

		$this->assertFalse( $list->hasTermForLanguage( 'EN' ) );
		$this->assertFalse( $list->hasTermForLanguage( ' de ' ) );
	}

	/**
	 * @dataProvider invalidLanguageCodeProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testGivenInvalidLanguageCode_hasTermForLanguageThrowsException( $languageCode ) {
		$list = new TermList();
		$list->hasTermForLanguage( $languageCode );
	}

	public function invalidLanguageCodeProvider() {
		return array(
			array( null ),
			array( 21 ),
			array( '' ),
		);
	}

	public function testGivenNotSetLanguageCode_removeByLanguageDoesNoOp() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$list->removeByLanguage( 'nl' );

		$this->assertCount( 2, $list );
	}

	public function testGivenSetLanguageCode_removeByLanguageRemovesIt() {
		$deTerm = new Term( 'de', 'bar' );

		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			$deTerm,
		) );

		$list->removeByLanguage( 'en' );

		$this->assertEquals(
			array( 'de' => $deTerm ),
			iterator_to_array( $list )
		);
	}

	/**
	 * @dataProvider invalidLanguageCodeProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testGivenInvalidLanguageCode_removeByLanguageThrowsException( $languageCode ) {
		$list = new TermList();
		$list->removeByLanguage( $languageCode );
	}

	public function testGivenTermForNewLanguage_setTermAddsTerm() {
		$enTerm = new Term( 'en', 'foo' );
		$deTerm = new Term( 'de', 'bar' );

		$list = new TermList( array( $enTerm ) );
		$expectedList = new TermList( array( $enTerm, $deTerm ) );

		$list->setTerm( $deTerm );

		$this->assertEquals( $expectedList, $list );
	}

	public function testGivenTermForExistingLanguage_setTermReplacesTerm() {
		$enTerm = new Term( 'en', 'foo' );
		$newEnTerm = new Term( 'en', 'bar' );

		$list = new TermList( array( $enTerm ) );
		$expectedList = new TermList( array( $newEnTerm ) );

		$list->setTerm( $newEnTerm );
		$this->assertEquals( $expectedList, $list );
	}

	public function testEmptyListEqualsEmptyList() {
		$list = new TermList();
		$this->assertTrue( $list->equals( new TermList() ) );
	}

	public function testFilledListEqualsItself() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$this->assertTrue( $list->equals( $list ) );
		$this->assertTrue( $list->equals( clone $list ) );
	}

	public function testDifferentListsDoNotEqual() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$this->assertFalse( $list->equals( new TermList() ) );

		$this->assertFalse( $list->equals(
			new TermList( array(
				new Term( 'en', 'foo' ),
			) )
		) );

		$this->assertFalse( $list->equals(
			new TermList( array(
				new Term( 'en', 'foo' ),
				new Term( 'de', 'HAX' ),
			) )
		) );

		$this->assertFalse( $list->equals(
			new TermList( array(
				new Term( 'en', 'foo' ),
				new Term( 'de', 'bar' ),
				new Term( 'nl', 'baz' ),
			) )
		) );
	}

	public function testGivenNonTermList_equalsReturnsFalse() {
		$list = new TermList();
		$this->assertFalse( $list->equals( null ) );
		$this->assertFalse( $list->equals( new \stdClass() ) );
	}

	public function testGivenListsThatOnlyDifferInOrder_equalsReturnsTrue() {
		$list = new TermList( array(
			new Term( 'en', 'foo' ),
			new Term( 'de', 'bar' ),
		) );

		$this->assertTrue( $list->equals(
			new TermList( array(
				new Term( 'de', 'bar' ),
				new Term( 'en', 'foo' ),
			) )
		) );
	}

	public function testGivenNonSetLanguageTerm_hasTermReturnsFalse() {
		$list = new TermList();
		$this->assertFalse( $list->hasTerm( new Term( 'en', 'kittens' ) ) );
	}

	public function testGivenMismatchingTerm_hasTermReturnsFalse() {
		$list = new TermList( array( new Term( 'en', 'cats' ) ) );
		$this->assertFalse( $list->hasTerm( new Term( 'en', 'kittens' ) ) );
	}

	public function testGivenMatchingTerm_hasTermReturnsTrue() {
		$list = new TermList( array( new Term( 'en', 'kittens' ) ) );
		$this->assertTrue( $list->hasTerm( new Term( 'en', 'kittens' ) ) );
	}

	public function testGivenValidArgs_setTermTextSetsTerm() {
		$list = new TermList();

		$list->setTextForLanguage( 'en', 'kittens' );

		$this->assertTrue( $list->getByLanguage( 'en' )->equals( new Term( 'en', 'kittens' ) ) );
	}

	public function testGivenInvalidLanguageCode_setTermTextThrowsException() {
		$list = new TermList();

		$this->setExpectedException( 'InvalidArgumentException' );
		$list->setTextForLanguage( null, 'kittens' );
	}

	public function testGivenInvalidTermText_setTermTextThrowsException() {
		$list = new TermList();

		$this->setExpectedException( 'InvalidArgumentException' );
		$list->setTextForLanguage( 'en', null );
	}

	public function testGivenEmptyList_getWithLanguagesReturnsEmptyList() {
		$list = new TermList();
		$this->assertEquals( new TermList(), $list->getWithLanguages( array() ) );
		$this->assertEquals( new TermList(), $list->getWithLanguages( array( 'en', 'de' ) ) );
	}

	public function testGivenNoLanguages_getWithLanguagesReturnsEmptyList() {
		$list = new TermList();
		$list->setTextForLanguage( 'en', 'foo' );
		$list->setTextForLanguage( 'de', 'bar' );

		$this->assertEquals( new TermList(), $list->getWithLanguages( array() ) );
	}

	public function testGivenAllLanguages_getWithLanguagesReturnsFullList() {
		$list = new TermList();
		$list->setTextForLanguage( 'en', 'foo' );
		$list->setTextForLanguage( 'de', 'bar' );

		$this->assertEquals( $list, $list->getWithLanguages( array( 'en', 'de' ) ) );
	}

	public function testGivenSomeLanguages_getWithLanguagesReturnsPartialList() {
		$list = new TermList();
		$list->setTextForLanguage( 'en', 'foo' );
		$list->setTextForLanguage( 'de', 'bar' );
		$list->setTextForLanguage( 'nl', 'baz' );
		$list->setTextForLanguage( 'fr', 'hax' );

		$expectedList = new TermList();
		$expectedList->setTextForLanguage( 'en', 'foo' );
		$expectedList->setTextForLanguage( 'nl', 'baz' );

		$this->assertEquals( $expectedList, $list->getWithLanguages( array( 'en', 'nl' ) ) );
	}

}
