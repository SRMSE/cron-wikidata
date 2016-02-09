<?php

namespace Wikibase\DataModel\Statement;

/**
 * A filter that only accepts statements with one or more references, and rejects all unreferenced
 * statements.
 *
 * @since 4.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ReferencedStatementFilter implements StatementFilter {

	/**
	 * @since 4.4
	 */
	const FILTER_TYPE = 'referenced';

	/**
	 * @param Statement $statement
	 *
	 * @return boolean
	 */
	public function statementMatches( Statement $statement ) {
		return count( $statement->getReferences() ) !== 0;
	}

}
