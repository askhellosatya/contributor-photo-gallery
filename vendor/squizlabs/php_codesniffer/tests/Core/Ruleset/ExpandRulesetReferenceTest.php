<?php
/**
 * Test the Ruleset::expandRulesetReference() method.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Ruleset;

use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Tests\ConfigDouble;
use PHP_CodeSniffer\Tests\Core\Ruleset\AbstractRulesetTestCase;

/**
 * Test various aspects of the Ruleset::expandRulesetReference() method not covered by other tests.
 *
 * @covers \PHP_CodeSniffer\Ruleset::expandRulesetReference
 */
final class ExpandRulesetReferenceTest extends AbstractRulesetTestCase {



	/**
	 * Test handling of path references relative to the originally included ruleset.
	 *
	 * @return void
	 */
	public function testRulesetRelativePathReferences() {
		// Set up the ruleset.
		$standard = __DIR__ . '/ExpandRulesetReferenceTest.xml';
		$config   = new ConfigDouble( array( "--standard=$standard" ) );
		$ruleset  = new Ruleset( $config );

		$expected = array(
			'ExternalA.CheckSomething.Valid'              => 'Fixtures\\ExternalA\\Sniffs\\CheckSomething\\ValidSniff',
			'TestStandard.ValidSniffs.RegisterEmptyArray' => 'Fixtures\\TestStandard\\Sniffs\\ValidSniffs\\RegisterEmptyArraySniff',
			'ExternalB.CheckMore.Valid'                   => 'Fixtures\\ExternalB\\Sniffs\\CheckMore\\ValidSniff',
		);

		$this->assertSame( $expected, $ruleset->sniffCodes );
	}//end testRulesetRelativePathReferences()


	/**
	 * Test that an exception is thrown if a ruleset contains an unresolvable reference.
	 *
	 * @param string $standard    The standard to use for the test.
	 * @param string $replacement The reference which will be used in the exception message.
	 *
	 * @dataProvider dataUnresolvableReferenceThrowsException
	 *
	 * @return void
	 */
	public function testUnresolvableReferenceThrowsException( $standard, $replacement ) {
		// Set up the ruleset.
		$standard = __DIR__ . '/' . $standard;
		$config   = new ConfigDouble( array( "--standard=$standard" ) );

		$exceptionMessage  = 'ERROR: Referenced sniff "%s" does not exist.' . PHP_EOL;
		$exceptionMessage .= 'ERROR: No sniffs were registered.' . PHP_EOL . PHP_EOL;
		$this->expectRuntimeExceptionMessage( sprintf( $exceptionMessage, $replacement ) );

		new Ruleset( $config );
	}//end testUnresolvableReferenceThrowsException()


	/**
	 * Data provider.
	 *
	 * @see testUnresolvableReferenceThrowsException()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataUnresolvableReferenceThrowsException() {
		$data = array(
			'Referencing a non-existent XML file' => array(
				'standard'    => 'ExpandRulesetReferenceMissingFileTest.xml',
				'replacement' => './MissingFile.xml',
			),
			'Referencing an invalid directory starting with "~"' => array(
				'standard'    => 'ExpandRulesetReferenceInvalidHomePathRefTest.xml',
				'replacement' => '~/src/Standards/Squiz/Sniffs/Files/',
			),
			'Referencing an unknown standard'     => array(
				'standard'    => 'ExpandRulesetReferenceUnknownStandardTest.xml',
				'replacement' => 'UnknownStandard',
			),
			'Referencing a non-existent category in a known standard' => array(
				'standard'    => 'ExpandRulesetReferenceUnknownCategoryTest.xml',
				'replacement' => 'TestStandard.UnknownCategory',
			),
			'Referencing a non-existent sniff in a known standard' => array(
				'standard'    => 'ExpandRulesetReferenceUnknownSniffTest.xml',
				'replacement' => 'TestStandard.InvalidSniffs.UnknownRule',
			),
			'Referencing an invalid error code - no standard name' => array(
				'standard'    => 'ExpandRulesetReferenceInvalidErrorCode1Test.xml',
				'replacement' => '.Invalid.Undetermined.Found',
			),
			'Referencing an invalid error code - no category name' => array(
				'standard'    => 'ExpandRulesetReferenceInvalidErrorCode2Test.xml',
				'replacement' => 'Standard..Undetermined.Found',
			),
			'Referencing an invalid error code - no sniff name' => array(
				'standard'    => 'ExpandRulesetReferenceInvalidErrorCode3Test.xml',
				'replacement' => 'Standard.Invalid..Found',
			),
		);

		// Add tests which are only relevant for case-sensitive OSes.
		if ( stripos( PHP_OS, 'WIN' ) === false ) {
			$data['Referencing an existing sniff, but there is a case mismatch (OS-dependent) [1]'] = array(
				'standard'    => 'ExpandRulesetReferenceCaseMismatch1Test.xml',
				'replacement' => 'psr12.functions.nullabletypedeclaration',
			);
			$data['Referencing an existing sniff, but there is a case mismatch (OS-dependent) [2]'] = array(
				'standard'    => 'ExpandRulesetReferenceCaseMismatch2Test.xml',
				'replacement' => 'PSR12.Functions.ReturntypeDeclaration',
			);
		}

		return $data;
	}//end dataUnresolvableReferenceThrowsException()
}//end class
