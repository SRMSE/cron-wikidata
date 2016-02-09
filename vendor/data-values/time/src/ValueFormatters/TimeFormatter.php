<?php

namespace ValueFormatters;

use DataValues\TimeValue;
use InvalidArgumentException;

/**
 * Basic plain text formatter for TimeValue objects that either delegates formatting to an other
 * formatter given via OPT_TIME_ISO_FORMATTER or outputs the timestamp as it is.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author H. Snater < mediawiki@snater.com >
 */
class TimeFormatter extends ValueFormatterBase {

	/**
	 * @deprecated since 0.7.1, use TimeValue::CALENDAR_GREGORIAN instead
	 */
	const CALENDAR_GREGORIAN = TimeValue::CALENDAR_GREGORIAN;

	/**
	 * @deprecated since 0.7.1, use TimeValue::CALENDAR_JULIAN instead
	 */
	const CALENDAR_JULIAN = TimeValue::CALENDAR_JULIAN;

	/**
	 * Option to localize calendar models. Must contain an array mapping known calendar model URIs
	 * to localized calendar model names.
	 */
	const OPT_CALENDARNAMES = 'calendars';

	/**
	 * Option for a custom timestamp formatter. Must contain an instance of a ValueFormatter
	 * subclass, capable of formatting TimeValue objects. The output of the custom formatter is
	 * threaded as plain text and passed through.
	 */
	const OPT_TIME_ISO_FORMATTER = 'time iso formatter';

	/**
	 * @see ValueFormatterBase::__construct
	 *
	 * @param FormatterOptions|null $options
	 */
	public function __construct( FormatterOptions $options = null ) {
		parent::__construct( $options );

		$this->defaultOption( self::OPT_CALENDARNAMES, array() );
		$this->defaultOption( self::OPT_TIME_ISO_FORMATTER, null );
	}

	/**
	 * @see ValueFormatter::format
	 *
	 * @param TimeValue $value
	 *
	 * @throws InvalidArgumentException
	 * @return string Plain text
	 */
	public function format( $value ) {
		if ( !( $value instanceof TimeValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a TimeValue.' );
		}

		$formatted = $value->getTime();

		$isoFormatter = $this->getOption( self::OPT_TIME_ISO_FORMATTER );
		if ( $isoFormatter instanceof ValueFormatter ) {
			$formatted = $isoFormatter->format( $value );
		}

		return $formatted;
	}

}
