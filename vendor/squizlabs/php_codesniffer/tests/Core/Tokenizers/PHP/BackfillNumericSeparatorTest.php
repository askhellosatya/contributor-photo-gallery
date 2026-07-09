<?php
/**
 * Tests the backfilling of numeric separators to PHP < 7.4.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2019 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class BackfillNumericSeparatorTest extends AbstractTokenizerTestCase {



	/**
	 * Test that numbers using numeric separators are tokenized correctly.
	 *
	 * @param string $marker The comment which prefaces the target token in the test file.
	 * @param string $type   The expected token type.
	 * @param string $value  The expected token content.
	 *
	 * @dataProvider dataTestBackfill
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testBackfill( $marker, $type, $value ) {
		$tokens     = $this->phpcsFile->getTokens();
		$number     = $this->getTargetToken( $marker, array( T_LNUMBER, T_DNUMBER ) );
		$tokenArray = $tokens[ $number ];

		$this->assertSame( constant( $type ), $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not ' . $type . ' (code)' );
		$this->assertSame( $type, $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not ' . $type . ' (type)' );
		$this->assertSame( $value, $tokenArray['content'] );
	}//end testBackfill()


	/**
	 * Data provider.
	 *
	 * @see testBackfill()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataTestBackfill() {
		$testHexType = 'T_LNUMBER';
		if ( PHP_INT_MAX < 0xCAFEF00D ) {
			$testHexType = 'T_DNUMBER';
		}

		$testHexMultipleType = 'T_LNUMBER';
		if ( PHP_INT_MAX < 0x42726F776E ) {
			$testHexMultipleType = 'T_DNUMBER';
		}

		$testIntMoreThanMaxType = 'T_LNUMBER';
		if ( PHP_INT_MAX < 10223372036854775807 ) {
			$testIntMoreThanMaxType = 'T_DNUMBER';
		}

		return array(
			'decimal integer'                             => array(
				'marker' => '/* testSimpleLNumber */',
				'type'   => 'T_LNUMBER',
				'value'  => '1_000_000_000',
			),
			'float'                                       => array(
				'marker' => '/* testSimpleDNumber */',
				'type'   => 'T_DNUMBER',
				'value'  => '107_925_284.88',
			),
			'float, scientific notation, negative exponent with sigh' => array(
				'marker' => '/* testFloat */',
				'type'   => 'T_DNUMBER',
				'value'  => '6.674_083e-11',
			),
			'float, scientific notation, positive exponent with sign' => array(
				'marker' => '/* testFloat2 */',
				'type'   => 'T_DNUMBER',
				'value'  => '6.674_083e+11',
			),
			'float, scientific notation, positive exponent without sign' => array(
				'marker' => '/* testFloat3 */',
				'type'   => 'T_DNUMBER',
				'value'  => '1_2.3_4e1_23',
			),
			'hexidecimal integer/float'                   => array(
				'marker' => '/* testHex */',
				'type'   => $testHexType,
				'value'  => '0xCAFE_F00D',
			),
			'hexidecimal integer/float with multiple underscores' => array(
				'marker' => '/* testHexMultiple */',
				'type'   => $testHexMultipleType,
				'value'  => '0x42_72_6F_77_6E',
			),
			'hexidecimal integer'                         => array(
				'marker' => '/* testHexInt */',
				'type'   => 'T_LNUMBER',
				'value'  => '0x42_72_6F',
			),
			'binary integer'                              => array(
				'marker' => '/* testBinary */',
				'type'   => 'T_LNUMBER',
				'value'  => '0b0101_1111',
			),
			'octal integer'                               => array(
				'marker' => '/* testOctal */',
				'type'   => 'T_LNUMBER',
				'value'  => '0137_041',
			),
			'octal integer using explicit octal notation' => array(
				'marker' => '/* testExplicitOctal */',
				'type'   => 'T_LNUMBER',
				'value'  => '0o137_041',
			),
			'octal integer using explicit octal notation with capital O' => array(
				'marker' => '/* testExplicitOctalCapitalised */',
				'type'   => 'T_LNUMBER',
				'value'  => '0O137_041',
			),
			'integer more than PHP_INT_MAX becomes a float' => array(
				'marker' => '/* testIntMoreThanMax */',
				'type'   => $testIntMoreThanMaxType,
				'value'  => '10_223_372_036_854_775_807',
			),
		);
	}//end dataTestBackfill()


	/**
	 * Test that numbers using numeric separators which are considered parse errors and/or
	 * which aren't relevant to the backfill, do not incorrectly trigger the backfill anyway.
	 *
	 * @param string                           $testMarker     The comment which prefaces the target token in the test file.
	 * @param array<array<string, int|string>> $expectedTokens The token type and content of the expected token sequence.
	 *
	 * @dataProvider dataNoBackfill
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testNoBackfill( $testMarker, $expectedTokens ) {
		$tokens = $this->phpcsFile->getTokens();
		$number = $this->getTargetToken( $testMarker, array( T_LNUMBER, T_DNUMBER ) );

		foreach ( $expectedTokens as $key => $expectedToken ) {
			$i = ( $number + $key );
			$this->assertSame(
				$expectedToken['code'],
				$tokens[ $i ]['code'],
				'Token tokenized as ' . Tokens::tokenName( $tokens[ $i ]['code'] ) . ', not ' . Tokens::tokenName( $expectedToken['code'] )
			);
			$this->assertSame( $expectedToken['content'], $tokens[ $i ]['content'] );
		}
	}//end testNoBackfill()


	/**
	 * Data provider.
	 *
	 * @see testBackfill()
	 *
	 * @return array<string, array<string, string|array<array<string, int|string>>>>
	 */
	public static function dataNoBackfill() {
		return array(
			'invalid: trailing underscore'                 => array(
				'testMarker'     => '/* testInvalid1 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '100',
					),
					array(
						'code'    => T_STRING,
						'content' => '_',
					),
				),
			),
			'invalid: two consecutive underscores'         => array(
				'testMarker'     => '/* testInvalid2 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '1',
					),
					array(
						'code'    => T_STRING,
						'content' => '__1',
					),
				),
			),
			'invalid: underscore directly before decimal point' => array(
				'testMarker'     => '/* testInvalid3 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '1',
					),
					array(
						'code'    => T_STRING,
						'content' => '_',
					),
					array(
						'code'    => T_DNUMBER,
						'content' => '.0',
					),
				),
			),
			'invalid: underscore directly after decimal point' => array(
				'testMarker'     => '/* testInvalid4 */',
				'expectedTokens' => array(
					array(
						'code'    => T_DNUMBER,
						'content' => '1.',
					),
					array(
						'code'    => T_STRING,
						'content' => '_0',
					),
				),
			),
			'invalid: hex int - underscore directly after x' => array(
				'testMarker'     => '/* testInvalid5 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '0',
					),
					array(
						'code'    => T_STRING,
						'content' => 'x_123',
					),
				),
			),
			'invalid: binary int - underscore directly after b' => array(
				'testMarker'     => '/* testInvalid6 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '0',
					),
					array(
						'code'    => T_STRING,
						'content' => 'b_101',
					),
				),
			),
			'invalid: scientific float - underscore directly before e' => array(
				'testMarker'     => '/* testInvalid7 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '1',
					),
					array(
						'code'    => T_STRING,
						'content' => '_e2',
					),
				),
			),
			'invalid: scientific float - underscore directly after e' => array(
				'testMarker'     => '/* testInvalid8 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '1',
					),
					array(
						'code'    => T_STRING,
						'content' => 'e_2',
					),
				),
			),
			'invalid: space between parts of the number'   => array(
				'testMarker'     => '/* testInvalid9 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '107_925_284',
					),
					array(
						'code'    => T_WHITESPACE,
						'content' => ' ',
					),
					array(
						'code'    => T_DNUMBER,
						'content' => '.88',
					),
				),
			),
			'invalid: comment within the number'           => array(
				'testMarker'     => '/* testInvalid10 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '107_925_284',
					),
					array(
						'code'    => T_COMMENT,
						'content' => '/*comment*/',
					),
					array(
						'code'    => T_DNUMBER,
						'content' => '.88',
					),
				),
			),
			'invalid: explicit octal int - underscore directly after o' => array(
				'testMarker'     => '/* testInvalid11 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '0',
					),
					array(
						'code'    => T_STRING,
						'content' => 'o_137',
					),
				),
			),
			'invalid: explicit octal int - underscore directly after capital O' => array(
				'testMarker'     => '/* testInvalid12 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '0',
					),
					array(
						'code'    => T_STRING,
						'content' => 'O_41',
					),
				),
			),
			'calculations should be untouched - int - int' => array(
				'testMarker'     => '/* testCalc1 */',
				'expectedTokens' => array(
					array(
						'code'    => T_LNUMBER,
						'content' => '667_083',
					),
					array(
						'code'    => T_WHITESPACE,
						'content' => ' ',
					),
					array(
						'code'    => T_MINUS,
						'content' => '-',
					),
					array(
						'code'    => T_WHITESPACE,
						'content' => ' ',
					),
					array(
						'code'    => T_LNUMBER,
						'content' => '11',
					),
				),
			),
			'calculations should be untouched - scientific float + int' => array(
				'testMarker'     => '/* test Calc2 */',
				'expectedTokens' => array(
					array(
						'code'    => T_DNUMBER,
						'content' => '6.674_08e3',
					),
					array(
						'code'    => T_WHITESPACE,
						'content' => ' ',
					),
					array(
						'code'    => T_PLUS,
						'content' => '+',
					),
					array(
						'code'    => T_WHITESPACE,
						'content' => ' ',
					),
					array(
						'code'    => T_LNUMBER,
						'content' => '11',
					),
				),
			),
		);
	}//end dataNoBackfill()
}//end class
