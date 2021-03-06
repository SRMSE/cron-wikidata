<?php

namespace Wikibase\DataModel\Entity;

use InvalidArgumentException;
use Wikibase\DataModel\Claim\Claims;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementList;
use Wikibase\DataModel\Statement\StatementListHolder;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * Represents a single Wikibase property.
 * See https://www.mediawiki.org/wiki/Wikibase/DataModel#Properties
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Property extends Entity implements StatementListHolder {

	const ENTITY_TYPE = 'property';

	/**
	 * @var PropertyId|null
	 */
	private $id;

	/**
	 * @var Fingerprint
	 */
	private $fingerprint;

	/**
	 * @var string The data type of the property.
	 */
	private $dataTypeId;

	/**
	 * @var StatementList
	 */
	private $statements;

	/**
	 * @since 1.0
	 *
	 * @param PropertyId|null $id
	 * @param Fingerprint|null $fingerprint
	 * @param string $dataTypeId The data type of the property. Not to be confused with the data
	 *  value type.
	 * @param StatementList|null $statements Since 1.1
	 */
	public function __construct(
		PropertyId $id = null,
		Fingerprint $fingerprint = null,
		$dataTypeId,
		StatementList $statements = null
	) {
		$this->id = $id;
		$this->fingerprint = $fingerprint ?: new Fingerprint();
		$this->setDataTypeId( $dataTypeId );
		$this->statements = $statements ?: new StatementList();
	}

	/**
	 * Returns the id of the entity or null if it does not have one.
	 *
	 * @since 0.1 return type changed in 0.3
	 *
	 * @return PropertyId|null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Can be integer since 0.1.
	 * Can be PropertyId since 0.5.
	 * Can be null since 1.0.
	 *
	 * @param PropertyId|int|null $id
	 *
	 * @throws InvalidArgumentException
	 */
	public function setId( $id ) {
		if ( $id === null || $id instanceof PropertyId ) {
			$this->id = $id;
		} elseif ( is_int( $id ) ) {
			$this->id = PropertyId::newFromNumber( $id );
		} else {
			throw new InvalidArgumentException( '$id must be an instance of PropertyId, an integer,'
				. ' or null' );
		}
	}

	/**
	 * @since 0.7.3
	 *
	 * @return Fingerprint
	 */
	public function getFingerprint() {
		return $this->fingerprint;
	}

	/**
	 * @since 0.7.3
	 *
	 * @param Fingerprint $fingerprint
	 */
	public function setFingerprint( Fingerprint $fingerprint ) {
		$this->fingerprint = $fingerprint;
	}

	/**
	 * @param string $languageCode
	 * @param string $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function setLabel( $languageCode, $value ) {
		$this->fingerprint->setLabel( $languageCode, $value );
	}

	/**
	 * @param string $languageCode
	 * @param string $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function setDescription( $languageCode, $value ) {
		$this->fingerprint->setDescription( $languageCode, $value );
	}

	/**
	 * @param string $languageCode
	 * @param string[] $aliases
	 *
	 * @throws InvalidArgumentException
	 */
	public function setAliases( $languageCode, array $aliases ) {
		$this->fingerprint->setAliasGroup( $languageCode, $aliases );
	}

	/**
	 * @since 0.4
	 *
	 * @param string $dataTypeId The data type of the property. Not to be confused with the data
	 *  value type.
	 *
	 * @throws InvalidArgumentException
	 */
	public function setDataTypeId( $dataTypeId ) {
		if ( !is_string( $dataTypeId ) ) {
			throw new InvalidArgumentException( '$dataTypeId must be a string' );
		}

		$this->dataTypeId = $dataTypeId;
	}

	/**
	 * @since 0.4
	 *
	 * @return string Returns the data type of the property (property type). Not to be confused with
	 *  the data value type.
	 */
	public function getDataTypeId() {
		return $this->dataTypeId;
	}

	/**
	 * @see Entity::getType
	 *
	 * @since 0.1
	 *
	 * @return string Returns the entity type "property".
	 */
	public function getType() {
		return self::ENTITY_TYPE;
	}

	/**
	 * @since 0.3
	 *
	 * @param string $dataTypeId The data type of the property. Not to be confused with the data
	 *  value type.
	 *
	 * @return Property
	 */
	public static function newFromType( $dataTypeId ) {
		return new self( null, null, $dataTypeId );
	}

	/**
	 * @see Comparable::equals
	 *
	 * Two properties are considered equal if they are of the same
	 * type and have the same value. The value does not include
	 * the id, so entities with the same value but different id
	 * are considered equal.
	 *
	 * @since 0.1
	 *
	 * @param mixed $target
	 *
	 * @return bool
	 */
	public function equals( $target ) {
		if ( $this === $target ) {
			return true;
		}

		return $target instanceof self
			&& $this->dataTypeId === $target->dataTypeId
			&& $this->fingerprint->equals( $target->fingerprint )
			&& $this->statements->equals( $target->statements );
	}

	/**
	 * Returns if the Property has no content.
	 * Having an id and type set does not count as having content.
	 *
	 * @since 0.1
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return $this->fingerprint->isEmpty()
			&& $this->statements->isEmpty();
	}

	/**
	 * Removes all content from the Property.
	 * The id and the type are not part of the content.
	 *
	 * @since 0.1
	 */
	public function clear() {
		$this->fingerprint = new Fingerprint();
	}

	/**
	 * @since 1.1
	 *
	 * @return StatementList
	 */
	public function getStatements() {
		return $this->statements;
	}

	/**
	 * @since 1.1
	 *
	 * @param StatementList $statements
	 */
	public function setStatements( StatementList $statements ) {
		$this->statements = $statements;
	}

	/**
	 * @deprecated since 1.0, use getStatements()->toArray() instead.
	 *
	 * @return Statement[]
	 */
	public function getClaims() {
		return $this->statements->toArray();
	}

	/**
	 * @deprecated since 1.0, use setStatements instead
	 *
	 * @param Claims $claims
	 */
	public function setClaims( Claims $claims ) {
		$this->statements = new StatementList( iterator_to_array( $claims ) );
	}

}
