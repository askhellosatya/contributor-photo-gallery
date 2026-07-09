<?php
/**
 * Tests the support of PHP 8.1 "readonly" keyword.
 *
 * @author    Jaroslav Hanslík <kukulich@kukulich.cz>
 * @copyright 2021 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class BackfillReadonlyTest extends AbstractTokenizerTestCase {



	/**
	 * Test that the "readonly" keyword is tokenized as such.
	 *
	 * @param string $testMarker  The comment which prefaces the target token in the test file.
	 * @param string $testContent Optional. The token content to look for.
	 *                            Defaults to lowercase "readonly".
	 *
	 * @dataProvider dataReadonly
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testReadonly( $testMarker, $testContent = 'readonly' ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_READONLY, T_STRING ), $testContent );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_READONLY, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_READONLY (code)' );
		$this->assertSame( 'T_READONLY', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_READONLY (type)' );
	}//end testReadonly()


	/**
	 * Data provider.
	 *
	 * @see testReadonly()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataReadonly() {
		return array(
			'property declaration, no visibility'         => array(
				'testMarker' => '/* testReadonlyProperty */',
			),
			'property declaration, var keyword before'    => array(
				'testMarker' => '/* testVarReadonlyProperty */',
			),
			'property declaration, var keyword after'     => array(
				'testMarker' => '/* testReadonlyVarProperty */',
			),
			'property declaration, static before'         => array(
				'testMarker' => '/* testStaticReadonlyProperty */',
			),
			'property declaration, static after'          => array(
				'testMarker' => '/* testReadonlyStaticProperty */',
			),
			'constant declaration, with visibility'       => array(
				'testMarker' => '/* testConstReadonlyProperty */',
			),
			'property declaration, missing type'          => array(
				'testMarker' => '/* testReadonlyPropertyWithoutType */',
			),
			'property declaration, public before'         => array(
				'testMarker' => '/* testPublicReadonlyProperty */',
			),
			'property declaration, protected before'      => array(
				'testMarker' => '/* testProtectedReadonlyProperty */',
			),
			'property declaration, private before'        => array(
				'testMarker' => '/* testPrivateReadonlyProperty */',
			),
			'property declaration, public after'          => array(
				'testMarker' => '/* testPublicReadonlyPropertyWithReadonlyFirst */',
			),
			'property declaration, protected after'       => array(
				'testMarker' => '/* testProtectedReadonlyPropertyWithReadonlyFirst */',
			),
			'property declaration, private after'         => array(
				'testMarker' => '/* testPrivateReadonlyPropertyWithReadonlyFirst */',
			),
			'property declaration, private before, comments in declaration' => array(
				'testMarker' => '/* testReadonlyWithCommentsInDeclaration */',
			),
			'property declaration, private before, nullable type' => array(
				'testMarker' => '/* testReadonlyWithNullableProperty */',
			),
			'property declaration, private before, union type, null first' => array(
				'testMarker' => '/* testReadonlyNullablePropertyWithUnionTypeHintAndNullFirst */',
			),
			'property declaration, private before, union type, null last' => array(
				'testMarker' => '/* testReadonlyNullablePropertyWithUnionTypeHintAndNullLast */',
			),
			'property declaration, private before, array type' => array(
				'testMarker' => '/* testReadonlyPropertyWithArrayTypeHint */',
			),
			'property declaration, private before, self type' => array(
				'testMarker' => '/* testReadonlyPropertyWithSelfTypeHint */',
			),
			'property declaration, private before, parent type' => array(
				'testMarker' => '/* testReadonlyPropertyWithParentTypeHint */',
			),
			'property declaration, private before, FQN type' => array(
				'testMarker' => '/* testReadonlyPropertyWithFullyQualifiedTypeHint */',
			),
			'property declaration, public before, mixed case' => array(
				'testMarker'  => '/* testReadonlyIsCaseInsensitive */',
				'testContent' => 'ReAdOnLy',
			),
			'property declaration, constructor property promotion' => array(
				'testMarker' => '/* testReadonlyConstructorPropertyPromotion */',
			),
			'property declaration, constructor property promotion with reference, mixed case' => array(
				'testMarker'  => '/* testReadonlyConstructorPropertyPromotionWithReference */',
				'testContent' => 'ReadOnly',
			),
			'property declaration, in anonymous class'    => array(
				'testMarker' => '/* testReadonlyPropertyInAnonymousClass */',
			),
			'property declaration, no visibility, DNF type, unqualified' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeUnqualified */',
			),
			'property declaration, public before, DNF type, fully qualified' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeFullyQualified */',
			),
			'property declaration, protected before, DNF type, partially qualified' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypePartiallyQualified */',
			),
			'property declaration, private before, DNF type, namespace relative name' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeRelativeName */',
			),
			'property declaration, private before, DNF type, multiple sets' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeMultipleSets */',
			),
			'property declaration, private before, DNF type, union with array' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeWithArray */',
			),
			'property declaration, private before, DNF type, with spaces and comment' => array(
				'testMarker' => '/* testReadonlyPropertyDNFTypeWithSpacesAndComments */',
			),
			'property declaration, constructor property promotion, DNF type' => array(
				'testMarker' => '/* testReadonlyConstructorPropertyPromotionWithDNF */',
			),
			'property declaration, constructor property promotion, DNF type and reference' => array(
				'testMarker' => '/* testReadonlyConstructorPropertyPromotionWithDNFAndReference */',
			),
			'anon class declaration, with parentheses'    => array(
				'testMarker' => '/* testReadonlyAnonClassWithParens */',
			),
			'anon class declaration, without parentheses' => array(
				'testMarker'  => '/* testReadonlyAnonClassWithoutParens */',
				'testContent' => 'Readonly',
			),
			'anon class declaration, with comments and whitespace' => array(
				'testMarker'  => '/* testReadonlyAnonClassWithCommentsAndWhitespace */',
				'testContent' => 'READONLY',
			),
			'live coding / parse error'                   => array(
				'testMarker' => '/* testParseErrorLiveCoding */',
			),
		);
	}//end dataReadonly()


	/**
	 * Test that "readonly" when not used as the keyword is still tokenized as `T_STRING`.
	 *
	 * @param string $testMarker  The comment which prefaces the target token in the test file.
	 * @param string $testContent Optional. The token content to look for.
	 *                            Defaults to lowercase "readonly".
	 *
	 * @dataProvider dataNotReadonly
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testNotReadonly( $testMarker, $testContent = 'readonly' ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_READONLY, T_STRING ), $testContent );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );
		$this->assertSame( 'T_STRING', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (type)' );
	}//end testNotReadonly()


	/**
	 * Data provider.
	 *
	 * @see testNotReadonly()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataNotReadonly() {
		return array(
			'name of a constant, context: declaration using "const" keyword, uppercase' => array(
				'testMarker'  => '/* testReadonlyUsedAsClassConstantName */',
				'testContent' => 'READONLY',
			),
			'name of a method, context: declaration'       => array(
				'testMarker' => '/* testReadonlyUsedAsMethodName */',
			),
			'name of a property, context: property access' => array(
				'testMarker' => '/* testReadonlyUsedAsPropertyName */',
			),
			'name of a property, context: property access in ternary' => array(
				'testMarker' => '/* testReadonlyPropertyInTernaryOperator */',
			),
			'name of a function, context: declaration'     => array(
				'testMarker' => '/* testReadonlyUsedAsFunctionName */',
			),
			'name of a function, context: declaration with return by ref' => array(
				'testMarker' => '/* testReadonlyUsedAsFunctionNameWithReturnByRef */',
			),
			'name of namespace, context: declaration, mixed case' => array(
				'testMarker'  => '/* testReadonlyUsedAsNamespaceName */',
				'testContent' => 'Readonly',
			),
			'partial name of namespace, context: declaration, mixed case' => array(
				'testMarker'  => '/* testReadonlyUsedAsPartOfNamespaceName */',
				'testContent' => 'Readonly',
			),
			'name of a function, context: call'            => array(
				'testMarker' => '/* testReadonlyAsFunctionCall */',
			),
			'name of a namespaced function, context: partially qualified call' => array(
				'testMarker' => '/* testReadonlyAsNamespacedFunctionCall */',
			),
			'name of a function, context: namespace relative call, mixed case' => array(
				'testMarker'  => '/* testReadonlyAsNamespaceRelativeFunctionCall */',
				'testContent' => 'ReadOnly',
			),
			'name of a method, context: method call on object' => array(
				'testMarker' => '/* testReadonlyAsMethodCall */',
			),
			'name of a method, context: nullsafe method call on object' => array(
				'testMarker'  => '/* testReadonlyAsNullsafeMethodCall */',
				'testContent' => 'readOnly',
			),
			'name of a method, context: static method call with space after' => array(
				'testMarker' => '/* testReadonlyAsStaticMethodCallWithSpace */',
			),
			'name of a constant, context: constant access - uppercase' => array(
				'testMarker'  => '/* testClassConstantFetchWithReadonlyAsConstantName */',
				'testContent' => 'READONLY',
			),
			'name of a function, context: call with space and comment between keyword and parens' => array(
				'testMarker' => '/* testReadonlyUsedAsFunctionCallWithSpaceBetweenKeywordAndParens */',
			),
			'name of a method, context: declaration with DNF parameter' => array(
				'testMarker' => '/* testReadonlyUsedAsMethodNameWithDNFParam */',
			),
		);
	}//end dataNotReadonly()
}//end class
