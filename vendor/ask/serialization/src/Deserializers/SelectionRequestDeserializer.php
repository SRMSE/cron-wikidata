<?php

namespace Ask\Deserializers;

use Ask\Deserializers\Strategies\SelectionRequestDeserializationStrategy;
use Deserializers\Deserializer;
use Deserializers\Exceptions\DeserializationException;
use Deserializers\StrategicDeserializer;
use Deserializers\TypedObjectDeserializer;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SelectionRequestDeserializer extends TypedObjectDeserializer {

	protected $dvDeserializer;
	protected $deserializer;

	public function __construct( Deserializer $dataValueDeserializer ) {
		$this->dvDeserializer = $dataValueDeserializer;
		$this->deserializer = $this->newDeserializer();
	}

	protected function newDeserializer() {
		return new StrategicDeserializer(
			new SelectionRequestDeserializationStrategy(
				$this->dvDeserializer,
				$this
			),
			'selectionRequest',
			'selectionRequestType'
		);
	}

	/**
	 * @see Deserializer::deserialize
	 *
	 * @since 1.0
	 *
	 * @param mixed $serialization
	 *
	 * @return object
	 * @throws DeserializationException
	 */
	public function deserialize( $serialization ) {
		return $this->deserializer->deserialize( $serialization );
	}

	/**
	 * @see Deserializer::isDeserializerFor
	 *
	 * @since 1.0
	 *
	 * @param mixed $serialization
	 *
	 * @return boolean
	 */
	public function isDeserializerFor( $serialization ) {
		return $this->deserializer->isDeserializerFor( $serialization );
	}

}
