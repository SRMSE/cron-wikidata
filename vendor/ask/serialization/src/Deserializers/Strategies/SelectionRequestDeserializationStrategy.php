<?php

namespace Ask\Deserializers\Strategies;

use Ask\Language\Selection\PropertySelection;
use Ask\Language\Selection\SubjectSelection;
use Deserializers\Deserializer;
use Deserializers\Exceptions\DeserializationException;
use Deserializers\Exceptions\InvalidAttributeException;
use Deserializers\TypedDeserializationStrategy;
use InvalidArgumentException;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SelectionRequestDeserializationStrategy extends TypedDeserializationStrategy {

	protected $dvDeserializer;

	public function __construct( Deserializer $dataValueDeserializer ) {
		$this->dvDeserializer = $dataValueDeserializer;
	}

	/**
	 * @see TypedDeserializationStrategy::getDeserializedValue
	 *
	 * @since 1.0
	 *
	 * @param string $selectionRequestType
	 * @param array $valueSerialization
	 *
	 * @return object
	 * @throws DeserializationException
	 */
	public function getDeserializedValue( $selectionRequestType, array $valueSerialization ) {
		switch ( $selectionRequestType ) {
			case 'property':
				return $this->newPropertySelectionRequest( $valueSerialization );
			case 'subject':
				return new SubjectSelection();
		}

		throw new InvalidAttributeException( 'selectionRequestType', $selectionRequestType );
	}

	protected function newPropertySelectionRequest( array $value ) {
		$this->requireAttribute( $value, 'property' );
		$this->assertAttributeIsArray( $value, 'property' );

		$propertyId = $this->dvDeserializer->deserialize( $value['property'] );

		return new PropertySelection( $propertyId );
	}

}
