<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\AutomatedValues\Tests\Unit;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use ProfessionalWiki\AutomatedValues\Domain\TemplatedAliasesSpec;
use ProfessionalWiki\AutomatedValues\Domain\TemplatedLabelSpec;
use ProfessionalWiki\AutomatedValues\Domain\Template;
use ProfessionalWiki\AutomatedValues\Domain\EntityCriteria;
use ProfessionalWiki\AutomatedValues\Domain\Rule;
use ProfessionalWiki\AutomatedValues\Domain\Rules;
use ProfessionalWiki\AutomatedValues\Domain\TemplateSegment;
use ProfessionalWiki\AutomatedValues\Domain\StatementEqualityCriterion;
use ProfessionalWiki\AutomatedValues\Presentation\RulesDeserializer;
use ProfessionalWiki\AutomatedValues\Presentation\RuleValidator;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers \ProfessionalWiki\AutomatedValues\Presentation\RulesDeserializer
 */
class RulesDeserializerTest extends TestCase {

	private const DEFAULT_LANGUAGE_CODES = [ 'nl', 'fr', 'de' ];

	public function testInvalidRulesResultInEmptyList(): void {
		$this->assertEquals(
			new Rules(),
			$this->newRulesDeserializer()->deserialize( '' )
		);
	}

	private function newRulesDeserializer(): RulesDeserializer {
		return new RulesDeserializer(
			RuleValidator::newInstance(),
			self::DEFAULT_LANGUAGE_CODES
		);
	}

	public function testExampleRules(): void {
		$rules = $this->newRulesDeserializer()->deserialize( file_get_contents( __DIR__ . '/../../rules.example.json' ) );

		$expectedRules = new Rules(
			new Rule(
				new EntityCriteria(
					new StatementEqualityCriterion(
						new PropertyId( 'P1' ),
						new StringValue( 'Q1' )
					)
				),
				new TemplatedLabelSpec(
					[ 'en' ],
					new Template(
						new TemplateSegment( '$ ', new PropertyId( 'P2' ), new PropertyId( 'P3' ) ),
						new TemplateSegment( '$', new PropertyId( 'P2' ), null ),
						new TemplateSegment( ', $', new PropertyId( 'P2' ), new PropertyId( 'P4' ) ),
					)
				),
				new TemplatedAliasesSpec(
					self::DEFAULT_LANGUAGE_CODES,
					new Template(
						new TemplateSegment( 'President ', new PropertyId( 'P5' ), new PropertyId( 'P3' ) ),
						new TemplateSegment( '$', new PropertyId( 'P5' ), null ),
						new TemplateSegment( ' $', new PropertyId( 'P5' ), new PropertyId( 'P4' ) ),
					)
				)
			)
		);

		$this->assertEquals(
			$expectedRules,
			$rules
		);
	}

}
