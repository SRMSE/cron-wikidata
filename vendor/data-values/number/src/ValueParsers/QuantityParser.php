<?php

namespace ValueParsers;

use DataValues\DecimalMath;
use DataValues\DecimalValue;
use DataValues\IllegalValueException;
use DataValues\QuantityValue;
use InvalidArgumentException;

/**
 * ValueParser that parses the string representation of a quantity.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
class QuantityParser extends StringValueParser {

	const FORMAT_NAME = 'quantity';

	/**
	 * The unit of the value to parse. If this option is given, it's illegal to also specify
	 * a unit in the input string.
	 *
	 * @since 0.5
	 */
	const OPT_UNIT = 'unit';

	/**
	 * @var DecimalParser
	 */
	private $decimalParser;

	/**
	 * @var NumberUnlocalizer
	 */
	private $unlocalizer;

	/**
	 * @since 0.1
	 *
	 * @param ParserOptions|null $options
	 * @param NumberUnlocalizer $unlocalizer
	 */
	public function __construct( ParserOptions $options = null, NumberUnlocalizer $unlocalizer = null ) {
		parent::__construct( $options );

		$this->defaultOption( self::OPT_UNIT, null );

		$this->unlocalizer = $unlocalizer ?: new BasicNumberUnlocalizer();
		$this->decimalParser = new DecimalParser( $this->options, $this->unlocalizer );
	}

	/**
	 * @see StringValueParser::stringParse
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return QuantityValue
	 * @throws ParseException
	 */
	protected function stringParse( $value ) {
		list( $amount, $exactness, $margin, $unit ) = $this->splitQuantityString( $value );

		$unitOption = $this->getUnitFromOptions();

		if ( $unit === null ) {
			$unit = $unitOption !== null ? $unitOption : '1';
		} elseif ( $unitOption !== null && $unit !== $unitOption ) {
			throw new ParseException( 'Cannot specify a unit in input if a unit was fixed via options.' );
		}

		try {
			$quantity = $this->newQuantityFromParts( $amount, $exactness, $margin, $unit );
			return $quantity;
		} catch ( IllegalValueException $ex ) {
			throw new ParseException( $ex->getMessage(), $value, self::FORMAT_NAME );
		}
	}

	/**
	 * @return string|null
	 */
	private function getUnitFromOptions() {
		$unit = $this->getOption( self::OPT_UNIT );
		return $unit === null ? null : trim( $unit );
	}

	/**
	 * Constructs a QuantityValue from the given parts.
	 *
	 * @see splitQuantityString
	 *
	 * @param string $amount decimal representation of the amount
	 * @param string|null $exactness either '!' to indicate an exact value,
	 *        or '~' for "automatic", or null if $margin should be used.
	 * @param string|null $margin decimal representation of the uncertainty margin
	 * @param string $unit the unit identifier (use "1" for unitless quantities).
	 *
	 * @throws ParseException if one of the decimals could not be parsed.
	 * @throws IllegalValueException if the QuantityValue could not be constructed
	 * @return QuantityValue
	 */
	private function newQuantityFromParts( $amount, $exactness, $margin, $unit ) {
		list( $amount, $exponent ) = $this->decimalParser->splitDecimalExponent( $amount );
		$amountValue = $this->decimalParser->parse( $amount );

		if ( $exactness === '!' ) {
			// the amount is an exact number
			$amountValue = $this->decimalParser->applyDecimalExponent( $amountValue, $exponent );
			$quantity = $this->newExactQuantity( $amountValue, $unit );
		} elseif ( $margin !== null ) {
			// uncertainty margin given
			// NOTE: the pattern for scientific notation is 2e3 +/- 1e2, so the exponents are treated separately.
			$marginValue = $this->decimalParser->parse( $margin );
			$amountValue = $this->decimalParser->applyDecimalExponent( $amountValue, $exponent );
			$quantity = $this->newUncertainQuantityFromMargin( $amountValue, $unit, $marginValue );
		} else {
			// derive uncertainty from given decimals
			// NOTE: with scientific notation, the exponent applies to the uncertainty bounds, too
			$quantity = $this->newUncertainQuantityFromDigits( $amountValue, $unit, $exponent );
		}

		return $quantity;
	}

	/**
	 * Splits a quantity string into its syntactic parts.
	 *
	 * @see newQuantityFromParts
	 *
	 * @param string $value
	 *
	 * @throws InvalidArgumentException If $value is not a string
	 * @throws ParseException If $value does not match the expected pattern
	 * @return array list( $amount, $exactness, $margin, $unit ).
	 *         Parts not present in $value will be null
	 */
	private function splitQuantityString( $value ) {
		if ( !is_string( $value ) ) {
			throw new InvalidArgumentException( '$value must be a string' );
		}

		//TODO: allow explicitly specifying the number of significant figures
		//TODO: allow explicitly specifying the uncertainty interval

		$numberPattern = $this->unlocalizer->getNumberRegex( '@' );
		$unitPattern = $this->unlocalizer->getUnitRegex( '@' );

		$pattern = '@^'
			. '\s*(' . $numberPattern . ')' // $1: amount
			. '\s*(?:'
				. '([~!])'  // $2: '!' for "exact", '~' for "approx", or nothing
				. '|(?:\+/?-|±)\s*(' . $numberPattern . ')' // $3: plus/minus offset (uncertainty margin)
				. '|' // or nothing
			. ')'
			. '\s*(' . $unitPattern . ')?' // $4: unit
			. '\s*$@u';

		if ( !preg_match( $pattern, $value, $groups ) ) {
			throw new ParseException( 'Malformed quantity', $value, self::FORMAT_NAME );
		}

		for ( $i = 1; $i <= 4; $i++ ) {
			if ( !isset( $groups[$i] ) ) {
				$groups[$i] = null;
			} elseif ( $groups[$i] === '' ) {
				$groups[$i] = null;
			}
		}

		array_shift( $groups ); // remove $groups[0]
		return $groups;
	}

	/**
	 * Returns a QuantityValue representing the given amount.
	 * The amount is assumed to be absolutely exact, that is,
	 * the upper and lower bound will be the same as the amount.
	 *
	 * @param DecimalValue $amount
	 * @param string $unit The quantity's unit (use "1" for unit-less quantities)
	 *
	 * @return QuantityValue
	 */
	private function newExactQuantity( DecimalValue $amount, $unit = '1' ) {
		$lowerBound = $amount;
		$upperBound = $amount;

		return new QuantityValue( $amount, $unit, $upperBound, $lowerBound );
	}

	/**
	 * Returns a QuantityValue representing the given amount, automatically assuming
	 * a level of uncertainty based on the digits given.
	 *
	 * The upper and lower bounds are determined automatically from the given
	 * digits by increasing resp. decreasing the least significant digit.
	 * E.g. "+0.01" would have upperBound "+0.02" and lowerBound "+0.01",
	 * while "-100" would have upperBound "-99" and lowerBound "-101".
	 *
	 * @param DecimalValue $amount The quantity
	 * @param string $unit The quantity's unit (use "1" for unit-less quantities)
	 * @param DecimalValue $margin
	 *
	 * @return QuantityValue
	 */
	private function newUncertainQuantityFromMargin( DecimalValue $amount, $unit = '1', DecimalValue $margin ) {
		$decimalMath = new DecimalMath();
		$margin = $margin->computeAbsolute();

		$lowerBound = $decimalMath->sum( $amount, $margin->computeComplement() );
		$upperBound = $decimalMath->sum( $amount, $margin );

		return new QuantityValue( $amount, $unit, $upperBound, $lowerBound );
	}

	/**
	 * Returns a QuantityValue representing the given amount, automatically assuming
	 * a level of uncertainty based on the digits given.
	 *
	 * The upper and lower bounds are determined automatically from the given
	 * digits by increasing resp. decreasing the least significant digit.
	 * E.g. "+0.01" would have upperBound "+0.02" and lowerBound "+0.01",
	 * while "-100" would have upperBound "-99" and lowerBound "-101".
	 *
	 * @param DecimalValue $amount The quantity
	 * @param string $unit The quantity's unit (use "1" for unit-less quantities)
	 * @param int $exponent Decimal exponent to apply
	 *
	 * @return QuantityValue
	 */
	private function newUncertainQuantityFromDigits( DecimalValue $amount, $unit = '1', $exponent = 0 ) {
		$math = new DecimalMath();

		if ( $amount->getSign() === '+' ) {
			$upperBound = $math->bump( $amount );
			$lowerBound = $math->slump( $amount );
		} else {
			$upperBound = $math->slump( $amount );
			$lowerBound = $math->bump( $amount );
		}

		$amount = $this->decimalParser->applyDecimalExponent( $amount, $exponent );
		$lowerBound = $this->decimalParser->applyDecimalExponent( $lowerBound, $exponent );
		$upperBound = $this->decimalParser->applyDecimalExponent( $upperBound, $exponent );

		return new QuantityValue( $amount, $unit, $upperBound, $lowerBound );
	}

}
