<?php

namespace Ask\Serializers;

use Ask\Language\Option\PropertyValueSortExpression;
use Ask\Language\Option\SortExpression;
use Serializers\DispatchableSerializer;
use Serializers\Exceptions\UnsupportedObjectException;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SortExpressionSerializer implements DispatchableSerializer {

	public function serialize( $object ) {
		$this->assertCanSerialize( $object );
		return $this->getSerializedSortExpression( $object );
	}

	protected function assertCanSerialize( $askObject ) {
		if ( !$this->isSerializerFor( $askObject ) ) {
			throw new UnsupportedObjectException( $askObject );
		}
	}

	protected function getSerializedSortExpression( SortExpression $expression ) {
		$valueArray = array(
			'direction' => $expression->getDirection(),
		);

		$valueArray = array_merge(
			$valueArray,
			$this->getExpressionValueSerialization( $expression )
		);

		return array(
			'objectType' => 'sortExpression',
			'sortExpressionType' => $expression->getType(),
			'value' => $valueArray,
		);
	}

	protected function getExpressionValueSerialization( SortExpression $expression ) {
		if ( $expression instanceof PropertyValueSortExpression ) {
			return array(
				'property' => $expression->getPropertyId()->toArray()
			);
		}

		throw new UnsupportedObjectException( $expression );
	}

	public function isSerializerFor( $askObject ) {
		return $askObject instanceof SortExpression;
	}

}
