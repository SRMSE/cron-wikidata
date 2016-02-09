<?php

namespace Tests\DataValues\Serializers;

use DataValues\DataValue;
use DataValues\NumberValue;
use DataValues\Serializers\DataValueSerializer;
use DataValues\StringValue;

/**
 * @covers DataValues\Serializers\DataValueSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DataValueSerializerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider notADataValueProvider
	 */
	public function testGivenNonDataValue_IsSerializerForReturnsFalse( $notAnObject ) {
		$serializer = new DataValueSerializer();

		$this->assertFalse( $serializer->isSerializerFor( $notAnObject ) );
	}

	public function notADataValueProvider() {
		return array(
			array( 0 ),
			array( null ),
			array( '' ),
			array( array() ),
			array( true ),
			array( 4.2 ),
			array( (object)array() ),
			array( new \Exception() ),
		);
	}

	/**
	 * @dataProvider dataValueProvider
	 */
	public function testGivenDataValue_IsSerializerForReturnsTrue( DataValue $dataValue ) {
		$serializer = new DataValueSerializer();

		$this->assertTrue( $serializer->isSerializerFor( $dataValue ) );
	}

	public function dataValueProvider() {
		return array(
			array( new StringValue( 'foo' ) ),
			array( new NumberValue( 42 ) ),
		);
	}

	/**
	 * @dataProvider notADataValueProvider
	 */
	public function testWhenGivenNonDataValue_SerializeThrowsException( $notAnObject ) {
		$serializer = new DataValueSerializer();

		$this->setExpectedException( 'Serializers\Exceptions\SerializationException' );
		$serializer->serialize( $notAnObject );
	}

	public function testWhenGivenDataValue_SerializeCallsToArray() {
		$returnValue = 'expected return value';

		$serializer = new DataValueSerializer();

		$dataValue = $this->getMock( 'DataValues\DataValue' );
		$dataValue->expects( $this->once() )
			->method( 'toArray' )
			->will( $this->returnValue( $returnValue ) );

		$this->assertEquals( $returnValue, $serializer->serialize( $dataValue ) );
	}

}
