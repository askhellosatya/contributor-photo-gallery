<?php
/**
 * Tests the tokenization of explicit octal notation to PHP < 8.1.
 *
 * @author    Mark Baker <mark@demon-angel.eu>
 * @copyright 2019 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class BackfillExplicitOctalNotationTest extends AbstractTokenizerTestCase {



	/**
	 * Test that explicitly-defined octal values are tokenized as a single number and not as a number and a string.
	 *
	 * @param string     $marker      The comment which prefaces the target token in the test file.
	 * @param string     $value       The expected content of the token.
	 * @param int|string $nextToken   The expected next token.
	 * @param string     $nextContent The expected content of the next token.
	 *
	 * @dataProvider dataExplicitOctalNotation
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testExplicitOctalNotation( $marker, $value, $nextToken, $nextContent ) {
		$tokens = $this->phpcsFile->getTokens();

		$number = $this->getTargetToken( $marker, array( T_LNUMBER ) );

		$this->assertSame( $value, $tokens[ $number ]['content'], 'Content of integer token does not match expectation' );

		$this->assertSame( $nextToken, $tokens[ ( $number + 1 ) ]['code'], 'Next token is not the expected type, but ' . $tokens[ ( $number + 1 ) ]['type'] );
		$this->assertSame( $nextContent, $tokens[ ( $number + 1 ) ]['content'], 'Next token did not have the expected contents' );
	}//end testExplicitOctalNotation()


	/**
	 * Data provider.
	 *
	 * @see testExplicitOctalNotation()
	 *
	 * @return array<string, array<string, int|string>>
	 */
	public static function dataExplicitOctalNotation() {
		return array(
			'Explicit octal'                 => array(
				'marker'      => '/* testExplicitOctal */',
				'value'       => '0o137041',
				'nextToken'   => T_SEMICOLON,
				'nextContent' => ';',
			),
			'Explicit octal - capitalized O' => array(
				'marker'      => '/* testExplicitOctalCapitalised */',
				'value'       => '0O137041',
				'nextToken'   => T_SEMICOLON,
				'nextContent' => ';',
			),
			'Explicit octal - with numeric literal separator' => array(
				'marker'      => '/* testExplicitOctalWithNumericSeparator */',
				'value'       => '0o137_041',
				'nextToken'   => T_SEMICOLON,
				'nextContent' => ';',
			),
			'Invalid explicit octal - numeric literal separator directly after "0o"' => array(
				'marker'      => '/* testInvalid1 */',
				'value'       => '0',
				'nextToken'   => T_STRING,
				'nextContent' => 'o_137',
			),
			'Invalid explicit octal - numeric literal separator directly after "0O" (capitalized O)' => array(
				'marker'      => '/* testInvalid2 */',
				'value'       => '0',
				'nextToken'   => T_STRING,
				'nextContent' => 'O_41',
			),
			'Invalid explicit octal - number out of octal range' => array(
				'marker'      => '/* testInvalid3 */',
				'value'       => '0',
				'nextToken'   => T_STRING,
				'nextContent' => 'o91',
			),
			'Invalid explicit octal - part of the number out of octal range' => array(
				'marker'      => '/* testInvalid4 */',
				'value'       => '0O2',
				'nextToken'   => T_LNUMBER,
				'nextContent' => '82',
			),
			'Invalid explicit octal - part of the number out of octal range with numeric literal separator after' => array(
				'marker'      => '/* testInvalid5 */',
				'value'       => '0o2',
				'nextToken'   => T_LNUMBER,
				'nextContent' => '8_2',
			),
			'Invalid explicit octal - part of the number out of octal range with numeric literal separator before' => array(
				'marker'      => '/* testInvalid6 */',
				'value'       => '0o2',
				'nextToken'   => T_STRING,
				'nextContent' => '_82',
			),
			'Invalid explicit octal - explicit notation without number' => array(
				'marker'      => '/* testInvalid7 */',
				'value'       => '0',
				'nextToken'   => T_STRING,
				'nextContent' => 'o',
			),
		);
	}//end dataExplicitOctalNotation()
}//end class
