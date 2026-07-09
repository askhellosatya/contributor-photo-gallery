<?php
/**
 * Tests the parenthesis indexes get set correctly.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\Tokenizer;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class CreateParenthesisNestingMapDNFTypesTest extends AbstractTokenizerTestCase {



	/**
	 * Test that parentheses when **not** used in a type declaration are correctly tokenized.
	 *
	 * @param string    $testMarker The comment prefacing the target token.
	 * @param int|false $owner      Optional. The parentheses owner or false when no parentheses owner is expected.
	 *
	 * @dataProvider dataNormalParentheses
	 * @covers       PHP_CodeSniffer\Tokenizers\Tokenizer::createParenthesisNestingMap
	 *
	 * @return void
	 */
	public function testNormalParentheses( $testMarker, $owner = false ) {
		$tokens = $this->phpcsFile->getTokens();

		$openPtr = $this->getTargetToken( $testMarker, array( T_OPEN_PARENTHESIS, T_TYPE_OPEN_PARENTHESIS ) );
		$opener  = $tokens[ $openPtr ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_OPEN_PARENTHESIS, $opener['code'], 'Token tokenized as ' . $opener['type'] . ', not T_OPEN_PARENTHESIS (code)' );

		if ( $owner !== false ) {
			$this->assertArrayHasKey( 'parenthesis_owner', $opener, 'Parenthesis owner is not set' );
			$this->assertSame( ( $openPtr + $owner ), $opener['parenthesis_owner'], 'Opener parenthesis owner is not the expected token' );
		} else {
			$this->assertArrayNotHasKey( 'parenthesis_owner', $opener, 'Parenthesis owner is set' );
		}

		$this->assertArrayHasKey( 'parenthesis_opener', $opener, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $opener, 'Parenthesis closer is not set' );
		$this->assertSame( $openPtr, $opener['parenthesis_opener'], 'Parenthesis opener is not the expected token' );

		$closePtr = $opener['parenthesis_closer'];
		$closer   = $tokens[ $closePtr ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_CLOSE_PARENTHESIS, $closer['code'], 'Token tokenized as ' . $closer['type'] . ', not T_CLOSE_PARENTHESIS (code)' );

		if ( $owner !== false ) {
			$this->assertArrayHasKey( 'parenthesis_owner', $closer, 'Parenthesis owner is not set' );
			$this->assertSame( ( $openPtr + $owner ), $closer['parenthesis_owner'], 'Closer parenthesis owner is not the expected token' );
		} else {
			$this->assertArrayNotHasKey( 'parenthesis_owner', $closer, 'Parenthesis owner is set' );
		}

		$this->assertArrayHasKey( 'parenthesis_opener', $closer, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $closer, 'Parenthesis closer is not set' );
		$this->assertSame( $closePtr, $closer['parenthesis_closer'], 'Parenthesis closer is not the expected token' );

		for ( $i = ( $openPtr + 1 ); $i < $closePtr; $i++ ) {
			$this->assertArrayHasKey( 'nested_parenthesis', $tokens[ $i ], "Nested parenthesis key not set on token $i ({$tokens[$i]['type']})" );
			$this->assertArrayHasKey( $openPtr, $tokens[ $i ]['nested_parenthesis'], 'Nested parenthesis is missing target parentheses set' );
			$this->assertSame( $closePtr, $tokens[ $i ]['nested_parenthesis'][ $openPtr ], 'Nested parenthesis closer not set correctly' );
		}
	}//end testNormalParentheses()


	/**
	 * Data provider.
	 *
	 * @see testNormalParentheses()
	 *
	 * @return array<string, array<string, string|int|false>>
	 */
	public static function dataNormalParentheses() {
		// "Owner" offsets are relative to the open parenthesis.
		return array(
			'parens without owner'                        => array(
				'testMarker' => '/* testParensNoOwner */',
			),
			'parens without owner in ternary then'        => array(
				'testMarker' => '/* testParensNoOwnerInTernary */',
			),
			'parens without owner in short ternary'       => array(
				'testMarker' => '/* testParensNoOwnerInShortTernary */',
			),
			'parens with owner: function; & in default value' => array(
				'testMarker' => '/* testParensOwnerFunctionAmpersandInDefaultValue */',
				'owner'      => -3,
			),
			'parens with owner: closure; param declared by & ref' => array(
				'testMarker' => '/* testParensOwnerClosureAmpersandParamRef */',
				'owner'      => -1,
			),
			'parens with owner: if'                       => array(
				'testMarker' => '/* testParensOwnerIf */',
				'owner'      => -2,
			),
			'parens without owner in if condition'        => array(
				'testMarker' => '/* testParensNoOwnerInIfCondition */',
			),
			'parens with owner: for'                      => array(
				'testMarker' => '/* testParensOwnerFor */',
				'owner'      => -2,
			),
			'parens without owner in for condition'       => array(
				'testMarker' => '/* testParensNoOwnerInForCondition */',
			),
			'parens with owner: match'                    => array(
				'testMarker' => '/* testParensOwnerMatch */',
				'owner'      => -1,
			),
			'parens with owner: array'                    => array(
				'testMarker' => '/* testParensOwnerArray */',
				'owner'      => -2,
			),
			'parens without owner in array; function call with & in callable' => array(
				'testMarker' => '/* testParensNoOwnerFunctionCallWithAmpersandInCallable */',
			),
			'parens with owner: fn; & in return value'    => array(
				'testMarker' => '/* testParensOwnerArrowFn */',
				'owner'      => -1,
			),
			'parens with owner: list with reference vars' => array(
				'testMarker' => '/* testParensOwnerListWithRefVars */',
				'owner'      => -1,
			),
			'parens without owner, function call with DNF look-a-like param' => array(
				'testMarker' => '/* testParensNoOwnerFunctionCallwithDNFLookALikeParam */',
			),

			'parens without owner in OO const default value' => array(
				'testMarker' => '/* testParensNoOwnerOOConstDefaultValue */',
			),
			'parens without owner in property default 1'  => array(
				'testMarker' => '/* testParensNoOwnerPropertyDefaultValue1 */',
			),
			'parens without owner in property default 2'  => array(
				'testMarker' => '/* testParensNoOwnerPropertyDefaultValue2 */',
			),
			'parens without owner in param default value' => array(
				'testMarker' => '/* testParensNoOwnerParamDefaultValue */',
			),
			'parens without owner in return statement 1'  => array(
				'testMarker' => '/* testParensNoOwnerInReturnValue1 */',
			),
			'parens without owner in return statement 2'  => array(
				'testMarker' => '/* testParensNoOwnerInReturnValue2 */',
			),
			'parens without owner in return statement 3'  => array(
				'testMarker' => '/* testParensNoOwnerInReturnValue3 */',
			),
			'parens with owner: closure; & in default value' => array(
				'testMarker' => '/* testParensOwnerClosureAmpersandInDefaultValue */',
				'owner'      => -2,
			),
			'parens with owner: fn; dnf used within'      => array(
				'testMarker' => '/* testParensOwnerArrowDNFUsedWithin */',
				'owner'      => -2,
			),
			'parens without owner: default value for param in arrow function' => array(
				'testMarker' => '/* testParensNoOwnerAmpersandInDefaultValue */',
			),
			'parens without owner in arrow function return expression' => array(
				'testMarker' => '/* testParensNoOwnerInArrowReturnExpression */',
			),
		);
	}//end dataNormalParentheses()


	/**
	 * Test that parentheses when used in a DNF type declaration are correctly tokenized.
	 *
	 * Includes verifying that:
	 * - the tokens between the parentheses all have a "nested_parenthesis" key.
	 * - all ampersands between the parentheses are tokenized as T_TYPE_INTERSECTION.
	 *
	 * @param string $testMarker The comment prefacing the target token.
	 *
	 * @dataProvider dataDNFTypeParentheses
	 * @covers       PHP_CodeSniffer\Tokenizers\Tokenizer::createParenthesisNestingMap
	 *
	 * @return void
	 */
	public function testDNFTypeParentheses( $testMarker ) {
		$tokens = $this->phpcsFile->getTokens();

		$openPtr = $this->getTargetToken( $testMarker, array( T_OPEN_PARENTHESIS, T_TYPE_OPEN_PARENTHESIS ) );
		$opener  = $tokens[ $openPtr ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_TYPE_OPEN_PARENTHESIS, $opener['code'], 'Token tokenized as ' . $opener['type'] . ', not T_TYPE_OPEN_PARENTHESIS (code)' );

		$this->assertArrayNotHasKey( 'parenthesis_owner', $opener, 'Parenthesis owner is set' );
		$this->assertArrayHasKey( 'parenthesis_opener', $opener, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $opener, 'Parenthesis closer is not set' );
		$this->assertSame( $openPtr, $opener['parenthesis_opener'], 'Parenthesis opener is not the expected token' );

		$closePtr = $opener['parenthesis_closer'];
		$closer   = $tokens[ $closePtr ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_TYPE_CLOSE_PARENTHESIS, $closer['code'], 'Token tokenized as ' . $closer['type'] . ', not T_TYPE_CLOSE_PARENTHESIS (code)' );

		$this->assertArrayNotHasKey( 'parenthesis_owner', $closer, 'Parenthesis owner is set' );
		$this->assertArrayHasKey( 'parenthesis_opener', $closer, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $closer, 'Parenthesis closer is not set' );
		$this->assertSame( $closePtr, $closer['parenthesis_closer'], 'Parenthesis closer is not the expected token' );

		for ( $i = ( $openPtr + 1 ); $i < $closePtr; $i++ ) {
			$this->assertArrayHasKey( 'nested_parenthesis', $tokens[ $i ], "Nested parenthesis key not set on token $i ({$tokens[$i]['type']})" );
			$this->assertArrayHasKey( $openPtr, $tokens[ $i ]['nested_parenthesis'], 'Nested parenthesis is missing target parentheses set' );
			$this->assertSame( $closePtr, $tokens[ $i ]['nested_parenthesis'][ $openPtr ], 'Nested parenthesis closer not set correctly' );
		}//end for
	}//end testDNFTypeParentheses()


	/**
	 * Data provider.
	 *
	 * @see testDNFTypeParentheses()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataDNFTypeParentheses() {
		return array(
			'OO const type: unqualified classes'           => array(
				'testMarker' => '/* testDNFTypeOOConstUnqualifiedClasses */',
			),
			'OO const type: modifiers in reverse order'    => array(
				'testMarker' => '/* testDNFTypeOOConstReverseModifierOrder */',
			),
			'OO const type: multi-dnf part 1'              => array(
				'testMarker' => '/* testDNFTypeOOConstMulti1 */',
			),
			'OO const type: multi-dnf part 2'              => array(
				'testMarker' => '/* testDNFTypeOOConstMulti2 */',
			),
			'OO const type: multi-dnf part 3'              => array(
				'testMarker' => '/* testDNFTypeOOConstMulti3 */',
			),
			'OO const type: namespace relative classes'    => array(
				'testMarker' => '/* testDNFTypeOOConstNamespaceRelative */',
			),
			'OO const type: partially qualified classes'   => array(
				'testMarker' => '/* testDNFTypeOOConstPartiallyQualified */',
			),
			'OO const type: fully qualified classes'       => array(
				'testMarker' => '/* testDNFTypeOOConstFullyQualified */',
			),

			'OO property type: unqualified classes'        => array(
				'testMarker' => '/* testDNFTypePropertyUnqualifiedClasses */',
			),
			'OO property type: modifiers in reverse order' => array(
				'testMarker' => '/* testDNFTypePropertyReverseModifierOrder */',
			),
			'OO property type: multi-dnf namespace relative classes' => array(
				'testMarker' => '/* testDNFTypePropertyMultiNamespaceRelative */',
			),
			'OO property type: multi-dnf partially qualified classes' => array(
				'testMarker' => '/* testDNFTypePropertyMultiPartiallyQualified */',
			),
			'OO property type: multi-dnf fully qualified classes' => array(
				'testMarker' => '/* testDNFTypePropertyMultiFullyQualified */',
			),

			'OO property type: multi-dnf with readonly keyword 1' => array(
				'testMarker' => '/* testDNFTypePropertyWithReadOnlyKeyword1 */',
			),
			'OO property type: multi-dnf with readonly keyword 2' => array(
				'testMarker' => '/* testDNFTypePropertyWithReadOnlyKeyword2 */',
			),
			'OO property type: with static and readonly keywords' => array(
				'testMarker' => '/* testDNFTypePropertyWithStaticAndReadOnlyKeywords */',
			),
			'OO property type: with only static keyword'   => array(
				'testMarker' => '/* testDNFTypePropertyWithOnlyStaticKeyword */',
			),
			'OO method param type: first param'            => array(
				'testMarker' => '/* testDNFTypeParam1WithAttribute */',
			),
			'OO method param type: second param, first DNF' => array(
				'testMarker' => '/* testDNFTypeParam2 */',
			),
			'OO method param type: second param, second DNF' => array(
				'testMarker' => '/* testDNFTypeParam3 */',
			),
			'OO method param type: namespace relative classes' => array(
				'testMarker' => '/* testDNFTypeParamNamespaceRelative */',
			),
			'OO method param type: partially qualified classes' => array(
				'testMarker' => '/* testDNFTypeParamPartiallyQualified */',
			),
			'OO method param type: fully qualified classes' => array(
				'testMarker' => '/* testDNFTypeParamFullyQualified */',
			),
			'Constructor property promotion with multi DNF 1' => array(
				'testMarker' => '/* testDNFTypeConstructorPropertyPromotion1 */',
			),
			'Constructor property promotion with multi DNF 2' => array(
				'testMarker' => '/* testDNFTypeConstructorPropertyPromotion2 */',
			),
			'OO method return type: multi DNF 1'           => array(
				'testMarker' => '/* testDNFTypeReturnType1 */',
			),
			'OO method return type: multi DNF 2'           => array(
				'testMarker' => '/* testDNFTypeReturnType2 */',
			),
			'OO abstract method return type: multi DNF 1'  => array(
				'testMarker' => '/* testDNFTypeAbstractMethodReturnType1 */',
			),
			'OO abstract method return type: multi DNF 2'  => array(
				'testMarker' => '/* testDNFTypeAbstractMethodReturnType2 */',
			),
			'OO method return type: namespace relative classes' => array(
				'testMarker' => '/* testDNFTypeReturnTypeNamespaceRelative */',
			),
			'OO method return type: partially qualified classes' => array(
				'testMarker' => '/* testDNFTypeReturnPartiallyQualified */',
			),
			'OO method return type: fully qualified classes' => array(
				'testMarker' => '/* testDNFTypeReturnFullyQualified */',
			),
			'function param type: with reference'          => array(
				'testMarker' => '/* testDNFTypeWithReference */',
			),
			'function param type: with spread'             => array(
				'testMarker' => '/* testDNFTypeWithSpreadOperator */',
			),
			'closure param type: with illegal nullable'    => array(
				'testMarker' => '/* testDNFTypeClosureParamIllegalNullable */',
			),
			'closure return type'                          => array(
				'testMarker' => '/* testDNFTypeClosureReturn */',
			),
			'arrow function param type'                    => array(
				'testMarker' => '/* testDNFTypeArrowParam */',
			),
			'arrow function return type'                   => array(
				'testMarker' => '/* testDNFTypeArrowReturnType */',
			),
			'arrow function param type with return by ref' => array(
				'testMarker' => '/* testDNFTypeArrowParamWithReturnByRef */',
			),

			'illegal syntax: unnecessary parentheses (no union)' => array(
				'testMarker' => '/* testDNFTypeParamIllegalUnnecessaryParens */',
			),
			'illegal syntax: union within parentheses, intersect outside' => array(
				'testMarker' => '/* testDNFTypeParamIllegalIntersectUnionReversed */',
			),
			'illegal syntax: nested parentheses'           => array(
				'testMarker' => '/* testDNFTypeParamIllegalNestedParens */',
			),
		);
	}//end dataDNFTypeParentheses()
}//end class
