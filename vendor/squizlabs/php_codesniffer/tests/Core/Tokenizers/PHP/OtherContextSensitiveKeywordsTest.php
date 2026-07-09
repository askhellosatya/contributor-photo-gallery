<?php
/**
 * Tests the conversion of PHPCS native context sensitive keyword tokens to T_STRING.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

/**
 * Tests the conversion of PHPCS native context sensitive keyword tokens to T_STRING.
 *
 * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
 * @covers PHP_CodeSniffer\Tokenizers\PHP::standardiseToken
 */
final class OtherContextSensitiveKeywordsTest extends AbstractTokenizerTestCase {



	/**
	 * Clear the "resolved tokens" cache before running this test as otherwise the code
	 * under test may not be run during the test.
	 *
	 * @beforeClass
	 *
	 * @return void
	 */
	public static function clearTokenCache() {
		parent::clearResolvedTokensCache();
	}//end clearTokenCache()


	/**
	 * Test that context sensitive keyword is tokenized as string when it should be string.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataStrings
	 *
	 * @return void
	 */
	public function testStrings( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_STRING, T_NULL, T_FALSE, T_TRUE, T_PARENT, T_SELF ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );
		$this->assertSame( 'T_STRING', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (type)' );
	}//end testStrings()


	/**
	 * Data provider.
	 *
	 * @see testStrings()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataStrings() {
		return array(
			'constant declaration: parent' => array( '/* testKeywordParentAsConstantNameShouldBeString */' ),
			'constant declaration: self'   => array( '/* testKeywordSelfAsConstantNameShouldBeString */' ),
			'constant declaration: false'  => array( '/* testKeywordFalseAsConstantNameShouldBeString */' ),
			'constant declaration: true'   => array( '/* testKeywordTrueAsConstantNameShouldBeString */' ),
			'constant declaration: null'   => array( '/* testKeywordNullAsConstantNameShouldBeString */' ),

			'function declaration with return by ref: self' => array( '/* testKeywordSelfAfterFunctionByRefShouldBeString */' ),
			'function declaration with return by ref: parent' => array( '/* testKeywordParentAfterFunctionByRefShouldBeString */' ),
			'function declaration with return by ref: false' => array( '/* testKeywordFalseAfterFunctionByRefShouldBeString */' ),
			'function declaration with return by ref: true' => array( '/* testKeywordTrueAfterFunctionByRefShouldBeString */' ),
			'function declaration with return by ref: null' => array( '/* testKeywordNullAfterFunctionByRefShouldBeString */' ),

			'function call: self'          => array( '/* testKeywordAsFunctionCallNameShouldBeStringSelf */' ),
			'function call: parent'        => array( '/* testKeywordAsFunctionCallNameShouldBeStringParent */' ),
			'function call: false'         => array( '/* testKeywordAsFunctionCallNameShouldBeStringFalse */' ),
			'function call: true'          => array( '/* testKeywordAsFunctionCallNameShouldBeStringTrue */' ),
			'function call: null; with comment between keyword and parentheses' => array( '/* testKeywordAsFunctionCallNameShouldBeStringNull */' ),

			'class instantiation: false'   => array( '/* testClassInstantiationFalseIsString */' ),
			'class instantiation: true'    => array( '/* testClassInstantiationTrueIsString */' ),
			'class instantiation: null'    => array( '/* testClassInstantiationNullIsString */' ),

			'constant declaration: false as name after type' => array( '/* testFalseIsNameForTypedConstant */' ),
			'constant declaration: true as name after type' => array( '/* testTrueIsNameForTypedConstant */' ),
			'constant declaration: null as name after type' => array( '/* testNullIsNameForTypedConstant */' ),
			'constant declaration: self as name after type' => array( '/* testSelfIsNameForTypedConstant */' ),
			'constant declaration: parent as name after type' => array( '/* testParentIsNameForTypedConstant */' ),
		);
	}//end dataStrings()


	/**
	 * Test that context sensitive keyword is tokenized as keyword when it should be keyword.
	 *
	 * @param string $testMarker        The comment which prefaces the target token in the test file.
	 * @param string $expectedTokenType The expected token type.
	 *
	 * @dataProvider dataKeywords
	 *
	 * @return void
	 */
	public function testKeywords( $testMarker, $expectedTokenType ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_STRING, T_NULL, T_FALSE, T_TRUE, T_PARENT, T_SELF ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame(
			constant( $expectedTokenType ),
			$tokenArray['code'],
			'Token tokenized as ' . $tokenArray['type'] . ', not ' . $expectedTokenType . ' (code)'
		);
		$this->assertSame(
			$expectedTokenType,
			$tokenArray['type'],
			'Token tokenized as ' . $tokenArray['type'] . ', not ' . $expectedTokenType . ' (type)'
		);
	}//end testKeywords()


	/**
	 * Data provider.
	 *
	 * @see testKeywords()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataKeywords() {
		return array(
			'self: param type declaration'                 => array(
				'testMarker'        => '/* testSelfIsKeyword */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: param type declaration'               => array(
				'testMarker'        => '/* testParentIsKeyword */',
				'expectedTokenType' => 'T_PARENT',
			),

			'parent: class instantiation'                  => array(
				'testMarker'        => '/* testClassInstantiationParentIsKeyword */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: class instantiation'                    => array(
				'testMarker'        => '/* testClassInstantiationSelfIsKeyword */',
				'expectedTokenType' => 'T_SELF',
			),

			'false: param type declaration'                => array(
				'testMarker'        => '/* testFalseIsKeywordAsParamType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: param type declaration'                 => array(
				'testMarker'        => '/* testTrueIsKeywordAsParamType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: param type declaration'                 => array(
				'testMarker'        => '/* testNullIsKeywordAsParamType */',
				'expectedTokenType' => 'T_NULL',
			),
			'false: return type declaration in union'      => array(
				'testMarker'        => '/* testFalseIsKeywordAsReturnType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: return type declaration in union'       => array(
				'testMarker'        => '/* testTrueIsKeywordAsReturnType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: return type declaration in union'       => array(
				'testMarker'        => '/* testNullIsKeywordAsReturnType */',
				'expectedTokenType' => 'T_NULL',
			),
			'false: in comparison'                         => array(
				'testMarker'        => '/* testFalseIsKeywordInComparison */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: in comparison'                          => array(
				'testMarker'        => '/* testTrueIsKeywordInComparison */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: in comparison'                          => array(
				'testMarker'        => '/* testNullIsKeywordInComparison */',
				'expectedTokenType' => 'T_NULL',
			),

			'false: type in OO constant declaration'       => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: type in OO constant declaration'        => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: type in OO constant declaration'        => array(
				'testMarker'        => '/* testNullIsKeywordAsConstType */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: type in OO constant declaration'        => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstType */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: type in OO constant declaration'      => array(
				'testMarker'        => '/* testParentIsKeywordAsConstType */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: value in constant declaration'         => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: value in constant declaration'          => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: value in constant declaration'          => array(
				'testMarker'        => '/* testNullIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: value in constant declaration'          => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: value in constant declaration'        => array(
				'testMarker'        => '/* testParentIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: type in property declaration'          => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: type in property declaration'           => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: type in property declaration'           => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyType */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: type in property declaration'           => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyType */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: type in property declaration'         => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyType */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: value in property declaration'         => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyDefault */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: value in property declaration'          => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyDefault */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: value in property declaration'          => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyDefault */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: value in property declaration'          => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyDefault */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: value in property declaration'        => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyDefault */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: first in union type for OO constant declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstUnionTypeFirst */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: first in union type for OO constant declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstUnionTypeFirst */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: first in union type for OO constant declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsConstUnionTypeFirst */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: first in union type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstUnionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in union type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstUnionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: middle in union type for OO constant declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstUnionTypeMiddle */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: middle in union type for OO constant declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstUnionTypeMiddle */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: middle in union type for OO constant declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsConstUnionTypeMiddle */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: middle in union type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstUnionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in union type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstUnionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: last in union type for OO constant declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstUnionTypeLast */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: last in union type for OO constant declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstUnionTypeLast */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: last in union type for OO constant declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsConstUnionTypeLast */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: last in union type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstUnionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in union type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstUnionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: first in union type for property declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyUnionTypeFirst */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: first in union type for property declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyUnionTypeFirst */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: first in union type for property declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyUnionTypeFirst */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: first in union type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyUnionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in union type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyUnionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: middle in union type for property declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyUnionTypeMiddle */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: middle in union type for property declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyUnionTypeMiddle */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: middle in union type for property declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyUnionTypeMiddle */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: middle in union type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyUnionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in union type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyUnionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: last in union type for property declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyUnionTypeLast */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: last in union type for property declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyUnionTypeLast */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: last in union type for property declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyUnionTypeLast */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: last in union type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyUnionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in union type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyUnionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: first in union type for param declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsParamUnionTypeFirst */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: first in union type for param declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsParamUnionTypeFirst */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: first in union type for param declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsParamUnionTypeFirst */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: first in union type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamUnionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in union type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamUnionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: middle in union type for param declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsParamUnionTypeMiddle */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: middle in union type for param declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsParamUnionTypeMiddle */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: middle in union type for param declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsParamUnionTypeMiddle */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: middle in union type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamUnionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in union type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamUnionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: last in union type for param declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsParamUnionTypeLast */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: last in union type for param declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsParamUnionTypeLast */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: last in union type for param declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsParamUnionTypeLast */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: last in union type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamUnionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in union type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamUnionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: first in union type for return declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsReturnUnionTypeFirst */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: first in union type for return declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsReturnUnionTypeFirst */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: first in union type for return declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsReturnUnionTypeFirst */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: first in union type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnUnionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in union type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnUnionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: middle in union type for return declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsReturnUnionTypeMiddle */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: middle in union type for return declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsReturnUnionTypeMiddle */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: middle in union type for return declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsReturnUnionTypeMiddle */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: middle in union type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnUnionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in union type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnUnionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: last in union type for return declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsReturnUnionTypeLast */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: last in union type for return declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsReturnUnionTypeLast */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: last in union type for return declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsReturnUnionTypeLast */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: last in union type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnUnionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in union type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnUnionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'self: first in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstIntersectionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstIntersectionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: middle in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: last in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstIntersectionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in intersection type for OO constant declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsConstIntersectionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'self: first in intersection type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyIntersectionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in intersection type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyIntersectionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: middle in intersection type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in intersection type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: last in intersection type for property declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyIntersectionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in intersection type for property declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyIntersectionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'self: first in intersection type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamIntersectionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in intersection type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamIntersectionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: middle in intersection type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in intersection type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: last in intersection type for param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamIntersectionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in intersection type for param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamIntersectionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'self: first in intersection type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnIntersectionTypeFirst */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: first in intersection type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnIntersectionTypeFirst */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: middle in intersection type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: middle in intersection type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnIntersectionTypeMiddle */',
				'expectedTokenType' => 'T_PARENT',
			),
			'self: last in intersection type for return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnIntersectionTypeLast */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: last in intersection type for return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnIntersectionTypeLast */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: DNF type in OO constant declaration'   => array(
				'testMarker'        => '/* testFalseIsKeywordAsConstDNFType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: DNF type in OO constant declaration'    => array(
				'testMarker'        => '/* testTrueIsKeywordAsConstDNFType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: DNF type in OO constant declaration'    => array(
				'testMarker'        => '/* testNullIsKeywordAsConstDNFType */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: DNF type in OO constant declaration'    => array(
				'testMarker'        => '/* testSelfIsKeywordAsConstDNFType */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: DNF type in OO constant declaration'  => array(
				'testMarker'        => '/* testParentIsKeywordAsConstDNFType */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: DNF type in property declaration'      => array(
				'testMarker'        => '/* testFalseIsKeywordAsPropertyDNFType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: DNF type in property declaration'       => array(
				'testMarker'        => '/* testTrueIsKeywordAsPropertyDNFType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: DNF type in property declaration'       => array(
				'testMarker'        => '/* testNullIsKeywordAsPropertyDNFType */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: DNF type in property declaration'       => array(
				'testMarker'        => '/* testSelfIsKeywordAsPropertyDNFType */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: DNF type in property declaration'     => array(
				'testMarker'        => '/* testParentIsKeywordAsPropertyDNFType */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: DNF type in function param declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsParamDNFType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'false: DNF type in function return declaration' => array(
				'testMarker'        => '/* testFalseIsKeywordAsReturnDNFType */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: DNF type in function param declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsParamDNFType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'true: DNF type in function return declaration' => array(
				'testMarker'        => '/* testTrueIsKeywordAsReturnDNFType */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: DNF type in function param declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsParamDNFType */',
				'expectedTokenType' => 'T_NULL',
			),
			'null: DNF type in function return declaration' => array(
				'testMarker'        => '/* testNullIsKeywordAsReturnDNFType */',
				'expectedTokenType' => 'T_NULL',
			),
			'self: DNF type in function param declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsParamDNFType */',
				'expectedTokenType' => 'T_SELF',
			),
			'self: DNF type in function return declaration' => array(
				'testMarker'        => '/* testSelfIsKeywordAsReturnDNFType */',
				'expectedTokenType' => 'T_SELF',
			),
			'parent: DNF type in function param declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsParamDNFType */',
				'expectedTokenType' => 'T_PARENT',
			),
			'parent: DNF type in function return declaration' => array(
				'testMarker'        => '/* testParentIsKeywordAsReturnDNFType */',
				'expectedTokenType' => 'T_PARENT',
			),

			'false: in comparison, uppercase'              => array(
				'testMarker'        => '/* testFalseIsKeywordUppercase */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: in comparison, mixed case'              => array(
				'testMarker'        => '/* testTrueIsKeywordMixedCase */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: in comparison, uppercase'               => array(
				'testMarker'        => '/* testNullIsKeywordUppercase */',
				'expectedTokenType' => 'T_NULL',
			),
			'false: in assignment, fully qualified'        => array(
				'testMarker'        => '/* testFullyQualifiedFalseIsKeyword */',
				'expectedTokenType' => 'T_FALSE',
			),
			'true: in assignment, fully qualified'         => array(
				'testMarker'        => '/* testFullyQualifiedTrueIsKeyword */',
				'expectedTokenType' => 'T_TRUE',
			),
			'null: in assignment, fully qualified'         => array(
				'testMarker'        => '/* testFullyQualifiedNullIsKeyword */',
				'expectedTokenType' => 'T_NULL',
			),
		);
	}//end dataKeywords()
}//end class
