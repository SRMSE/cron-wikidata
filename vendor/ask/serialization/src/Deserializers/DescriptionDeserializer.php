<?php

namespace Ask\Deserializers;

use Ask\Deserializers\Strategies\DescriptionDeserializationStrategy;
use Deserializers\Deserializer;
use Deserializers\DispatchableDeserializer;
use Deserializers\Exceptions\DeserializationException;
use Deserializers\StrategicDeserializer;

/**
 * TODO: split individual description handling to own classes to we can use
 * polymorphic dispatch and not have this big OCP violation.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DescriptionDeserializer implements DispatchableDeserializer {

	protected $dvDeserializer;

	/**
	 * @var Deserializer
	 */
	protected $deserializer;

	public function __construct( Deserializer $dataValueDeserializer ) {
		$this->dvDeserializer = $dataValueDeserializer;
		$this->deserializer = $this->newDeserializer();
	}

	protected function newDeserializer() {
		return new StrategicDeserializer(
			new DescriptionDeserializationStrategy(
				$this->dvDeserializer,
				$this
			),
			'description',
			'descriptionType'
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
