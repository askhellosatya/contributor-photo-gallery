<?php
/**
 * Tests that embedded variables and expressions in double quoted strings are tokenized
 * as one double quoted string token.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2022 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class DoubleQuotedStringTest extends AbstractTokenizerTestCase {



	/**
	 * Test that double quoted strings contain the complete string.
	 *
	 * @param string $testMarker      The comment which prefaces the target token in the test file.
	 * @param string $expectedContent The expected content of the double quoted string.
	 *
	 * @dataProvider dataDoubleQuotedString
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testDoubleQuotedString( $testMarker, $expectedContent ) {
		$tokens = $this->phpcsFile->getTokens();

		$target = $this->getTargetToken( $testMarker, T_DOUBLE_QUOTED_STRING );
		$this->assertSame( $expectedContent, $tokens[ $target ]['content'] );
	}//end testDoubleQuotedString()


	/**
	 * Data provider.
	 *
	 * Type reference:
	 * 1. Directly embedded variables.
	 * 2. Braces outside the variable.
	 * 3. Braces after the dollar sign.
	 * 4. Variable variables and expressions.
	 *
	 * @link https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation
	 *
	 * @see testDoubleQuotedString()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataDoubleQuotedString() {
		return array(
			'Type 1: simple variable'           => array(
				'testMarker'      => '/* testSimple1 */',
				'expectedContent' => '"$foo"',
			),
			'Type 2: simple variable'           => array(
				'testMarker'      => '/* testSimple2 */',
				'expectedContent' => '"{$foo}"',
			),
			'Type 3: simple variable'           => array(
				'testMarker'      => '/* testSimple3 */',
				'expectedContent' => '"${foo}"',
			),
			'Type 1: array offset'              => array(
				'testMarker'      => '/* testDIM1 */',
				'expectedContent' => '"$foo[bar]"',
			),
			'Type 2: array offset'              => array(
				'testMarker'      => '/* testDIM2 */',
				'expectedContent' => '"{$foo[\'bar\']}"',
			),
			'Type 3: array offset'              => array(
				'testMarker'      => '/* testDIM3 */',
				'expectedContent' => '"${foo[\'bar\']}"',
			),
			'Type 1: object property'           => array(
				'testMarker'      => '/* testProperty1 */',
				'expectedContent' => '"$foo->bar"',
			),
			'Type 2: object property'           => array(
				'testMarker'      => '/* testProperty2 */',
				'expectedContent' => '"{$foo->bar}"',
			),
			'Type 2: object method call'        => array(
				'testMarker'      => '/* testMethod1 */',
				'expectedContent' => '"{$foo->bar()}"',
			),
			'Type 2: closure function call'     => array(
				'testMarker'      => '/* testClosure1 */',
				'expectedContent' => '"{$foo()}"',
			),
			'Type 2: chaining various syntaxes' => array(
				'testMarker'      => '/* testChain1 */',
				'expectedContent' => '"{$foo[\'bar\']->baz()()}"',
			),
			'Type 4: variable variables'        => array(
				'testMarker'      => '/* testVariableVar1 */',
				'expectedContent' => '"${$bar}"',
			),
			'Type 4: variable constants'        => array(
				'testMarker'      => '/* testVariableVar2 */',
				'expectedContent' => '"${(foo)}"',
			),
			'Type 4: object property'           => array(
				'testMarker'      => '/* testVariableVar3 */',
				'expectedContent' => '"${foo->bar}"',
			),
			'Type 4: variable variable nested in array offset' => array(
				'testMarker'      => '/* testNested1 */',
				'expectedContent' => '"${foo["${bar}"]}"',
			),
			'Type 4: variable array offset nested in array offset' => array(
				'testMarker'      => '/* testNested2 */',
				'expectedContent' => '"${foo["${bar[\'baz\']}"]}"',
			),
			'Type 4: variable object property'  => array(
				'testMarker'      => '/* testNested3 */',
				'expectedContent' => '"${foo->{$baz}}"',
			),
			'Type 4: variable object property - complex with single quotes' => array(
				'testMarker'      => '/* testNested4 */',
				'expectedContent' => '"${foo->{${\'a\'}}}"',
			),
			'Type 4: variable object property - complex with single and double quotes' => array(
				'testMarker'      => '/* testNested5 */',
				'expectedContent' => '"${foo->{"${\'a\'}"}}"',
			),
			'Type 4: live coding/parse error'   => array(
				'testMarker'      => '/* testParseError */',
				'expectedContent' => '"${foo["${bar
',
			),
		);
	}//end dataDoubleQuotedString()
}//end class
