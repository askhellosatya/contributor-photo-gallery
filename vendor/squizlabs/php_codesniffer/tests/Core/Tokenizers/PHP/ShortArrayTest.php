<?php
/**
 * Tests the conversion of square bracket tokens to short array tokens.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class ShortArrayTest extends AbstractTokenizerTestCase {



	/**
	 * Test that real square brackets are still tokenized as square brackets.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataSquareBrackets
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testSquareBrackets( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$opener     = $this->getTargetToken( $testMarker, array( T_OPEN_SQUARE_BRACKET, T_OPEN_SHORT_ARRAY ) );
		$tokenArray = $tokens[ $opener ];

		$this->assertSame( T_OPEN_SQUARE_BRACKET, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_OPEN_SQUARE_BRACKET (code)' );
		$this->assertSame( 'T_OPEN_SQUARE_BRACKET', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_OPEN_SQUARE_BRACKET (type)' );

		if ( isset( $tokens[ $opener ]['bracket_closer'] ) === true ) {
			$closer     = $tokens[ $opener ]['bracket_closer'];
			$tokenArray = $tokens[ $closer ];

			$this->assertSame( T_CLOSE_SQUARE_BRACKET, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_CLOSE_SQUARE_BRACKET (code)' );
			$this->assertSame( 'T_CLOSE_SQUARE_BRACKET', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_CLOSE_SQUARE_BRACKET (type)' );
		}
	}//end testSquareBrackets()


	/**
	 * Data provider.
	 *
	 * @see testSquareBrackets()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataSquareBrackets() {
		return array(
			'array access 1'                      => array( '/* testArrayAccess1 */' ),
			'array access 2'                      => array( '/* testArrayAccess2 */' ),
			'array assignment'                    => array( '/* testArrayAssignment */' ),
			'function call dereferencing'         => array( '/* testFunctionCallDereferencing */' ),
			'method call dereferencing'           => array( '/* testMethodCallDereferencing */' ),
			'static method call dereferencing'    => array( '/* testStaticMethodCallDereferencing */' ),
			'property dereferencing'              => array( '/* testPropertyDereferencing */' ),
			'property dereferencing with inaccessable name' => array( '/* testPropertyDereferencingWithInaccessibleName */' ),
			'static property dereferencing'       => array( '/* testStaticPropertyDereferencing */' ),
			'string dereferencing single quotes'  => array( '/* testStringDereferencing */' ),
			'string dereferencing double quotes'  => array( '/* testStringDereferencingDoubleQuoted */' ),
			'global constant dereferencing'       => array( '/* testConstantDereferencing */' ),
			'class constant dereferencing'        => array( '/* testClassConstantDereferencing */' ),
			'magic constant dereferencing'        => array( '/* testMagicConstantDereferencing */' ),
			'array access with curly braces'      => array( '/* testArrayAccessCurlyBraces */' ),
			'array literal dereferencing'         => array( '/* testArrayLiteralDereferencing */' ),
			'short array literal dereferencing'   => array( '/* testShortArrayLiteralDereferencing */' ),
			'class member dereferencing on instantiation 1' => array( '/* testClassMemberDereferencingOnInstantiation1 */' ),
			'class member dereferencing on instantiation 2' => array( '/* testClassMemberDereferencingOnInstantiation2 */' ),
			'class member dereferencing on clone' => array( '/* testClassMemberDereferencingOnClone */' ),
			'nullsafe method call dereferencing'  => array( '/* testNullsafeMethodCallDereferencing */' ),
			'interpolated string dereferencing'   => array( '/* testInterpolatedStringDereferencing */' ),
			'new anonymous class expression dereferencing 1' => array( '/* testNewAnonClassNoParenthesesExpressionDereferencing */' ),
			'new anonymous class expression dereferencing 2' => array( '/* testNewAnonClassParenthesesExpressionDereferencing */' ),
			'new anonymous class expression dereferencing 3' => array( '/* testNewAnonClassExtendsExpressionDereferencing */' ),
			'new anonymous class expression dereferencing 4' => array( '/* testNewAnonClassImplementsExpressionDereferencing */' ),
			'live coding'                         => array( '/* testLiveCoding */' ),
		);
	}//end dataSquareBrackets()


	/**
	 * Test that short arrays and short lists are still tokenized as short arrays.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataShortArrays
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testShortArrays( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$opener     = $this->getTargetToken( $testMarker, array( T_OPEN_SQUARE_BRACKET, T_OPEN_SHORT_ARRAY ) );
		$tokenArray = $tokens[ $opener ];

		$this->assertSame( T_OPEN_SHORT_ARRAY, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_OPEN_SHORT_ARRAY (code)' );
		$this->assertSame( 'T_OPEN_SHORT_ARRAY', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_OPEN_SHORT_ARRAY (type)' );

		if ( isset( $tokens[ $opener ]['bracket_closer'] ) === true ) {
			$closer     = $tokens[ $opener ]['bracket_closer'];
			$tokenArray = $tokens[ $closer ];

			$this->assertSame( T_CLOSE_SHORT_ARRAY, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_CLOSE_SHORT_ARRAY (code)' );
			$this->assertSame( 'T_CLOSE_SHORT_ARRAY', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_CLOSE_SHORT_ARRAY (type)' );
		}
	}//end testShortArrays()


	/**
	 * Data provider.
	 *
	 * @see testShortArrays()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataShortArrays() {
		return array(
			'short array empty'                         => array( '/* testShortArrayDeclarationEmpty */' ),
			'short array with value'                    => array( '/* testShortArrayDeclarationWithOneValue */' ),
			'short array with values'                   => array( '/* testShortArrayDeclarationWithMultipleValues */' ),
			'short array with dereferencing'            => array( '/* testShortArrayDeclarationWithDereferencing */' ),
			'short list'                                => array( '/* testShortListDeclaration */' ),
			'short list nested'                         => array( '/* testNestedListDeclaration */' ),
			'short array within function call'          => array( '/* testArrayWithinFunctionCall */' ),
			'short list after braced control structure' => array( '/* testShortListDeclarationAfterBracedControlStructure */' ),
			'short list after non-braced control structure' => array( '/* testShortListDeclarationAfterNonBracedControlStructure */' ),
			'short list after alternative control structure' => array( '/* testShortListDeclarationAfterAlternativeControlStructure */' ),
			'short list after class declaration'        => array( '/* testShortListDeclarationAfterClassDeclaration */' ),
		);
	}//end dataShortArrays()
}//end class
