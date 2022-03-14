<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\AutomatedValues\Domain;

use Wikibase\DataModel\Statement\StatementList;
use Wikibase\DataModel\Term\TermList;

class TemplatedLabelSpec implements LabelSpec {

	/**
	 * @var string[]
	 */
	private array $languageCodes;

	private Template $buildSpecification;

	/**
	 * @param string[] $languageCodes
	 * @param Template $buildSpecification
	 */
	public function __construct( array $languageCodes, Template $buildSpecification ) {
		$this->languageCodes = $languageCodes;
		$this->buildSpecification = $buildSpecification;
	}

	public function applyTo( TermList $labels, StatementList $statements ): void {
		$label = ( new ValueBuilder() )->buildValue( $this->buildSpecification, $statements );

		foreach ( $this->languageCodes as $languageCode ) {
			$labels->setTextForLanguage( $languageCode, $label );
		}
	}

}
