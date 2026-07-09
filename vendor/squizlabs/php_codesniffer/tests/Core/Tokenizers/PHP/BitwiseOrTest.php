<?php
/**
 * Tests the conversion of bitwise or tokens to type union tokens.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class BitwiseOrTest extends AbstractTokenizerTestCase {



	/**
	 * Test that non-union type bitwise or tokens are still tokenized as bitwise or.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataBitwiseOr
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testBitwiseOr( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_BITWISE_OR, T_TYPE_UNION ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_BITWISE_OR, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_BITWISE_OR (code)' );
		$this->assertSame( 'T_BITWISE_OR', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_BITWISE_OR (type)' );
	}//end testBitwiseOr()


	/**
	 * Data provider.
	 *
	 * @see testBitwiseOr()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataBitwiseOr() {
		return array(
			'in simple assignment 1'                     => array( '/* testBitwiseOr1 */' ),
			'in simple assignment 2'                     => array( '/* testBitwiseOr2 */' ),
			'in OO constant default value'               => array( '/* testBitwiseOrOOConstDefaultValue */' ),
			'in property default value'                  => array( '/* testBitwiseOrPropertyDefaultValue */' ),
			'in method parameter default value'          => array( '/* testBitwiseOrParamDefaultValue */' ),
			'in return statement'                        => array( '/* testBitwiseOr3 */' ),
			'in closure parameter default value'         => array( '/* testBitwiseOrClosureParamDefault */' ),
			'in OO constant default value DNF-like'      => array( '/* testBitwiseOrOOConstDefaultValueDNF */' ),
			'in property default value DNF-like'         => array( '/* testBitwiseOrPropertyDefaultValueDNF */' ),
			'in method parameter default value DNF-like' => array( '/* testBitwiseOrParamDefaultValueDNF */' ),
			'in arrow function parameter default value'  => array( '/* testBitwiseOrArrowParamDefault */' ),
			'in arrow function return expression'        => array( '/* testBitwiseOrArrowExpression */' ),
			'in long array key'                          => array( '/* testBitwiseOrInArrayKey */' ),
			'in long array value'                        => array( '/* testBitwiseOrInArrayValue */' ),
			'in short array key'                         => array( '/* testBitwiseOrInShortArrayKey */' ),
			'in short array value'                       => array( '/* testBitwiseOrInShortArrayValue */' ),
			'in catch condition'                         => array( '/* testBitwiseOrTryCatch */' ),
			'in parameter in function call'              => array( '/* testBitwiseOrNonArrowFnFunctionCall */' ),
			'live coding / undetermined'                 => array( '/* testLiveCoding */' ),
		);
	}//end dataBitwiseOr()


	/**
	 * Test that bitwise or tokens when used as part of a union type are tokenized as `T_TYPE_UNION`.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataTypeUnion
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testTypeUnion( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_BITWISE_OR, T_TYPE_UNION ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_TYPE_UNION, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_TYPE_UNION (code)' );
		$this->assertSame( 'T_TYPE_UNION', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_TYPE_UNION (type)' );
	}//end testTypeUnion()


	/**
	 * Data provider.
	 *
	 * @see testTypeUnion()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataTypeUnion() {
		return array(
			'type for OO constant'                         => array( '/* testTypeUnionOOConstSimple */' ),
			'type for OO constant, reversed modifier order' => array( '/* testTypeUnionOOConstReverseModifierOrder */' ),
			'type for OO constant, first of multi-union'   => array( '/* testTypeUnionOOConstMulti1 */' ),
			'type for OO constant, middle of multi-union + comments' => array( '/* testTypeUnionOOConstMulti2 */' ),
			'type for OO constant, last of multi-union'    => array( '/* testTypeUnionOOConstMulti3 */' ),
			'type for OO constant, using namespace relative names' => array( '/* testTypeUnionOOConstNamespaceRelative */' ),
			'type for OO constant, using partially qualified names' => array( '/* testTypeUnionOOConstPartiallyQualified */' ),
			'type for OO constant, using fully qualified names' => array( '/* testTypeUnionOOConstFullyQualified */' ),
			'type for static property'                     => array( '/* testTypeUnionPropertySimple */' ),
			'type for static property, reversed modifier order' => array( '/* testTypeUnionPropertyReverseModifierOrder */' ),
			'type for property, first of multi-union'      => array( '/* testTypeUnionPropertyMulti1 */' ),
			'type for property, middle of multi-union, also comments' => array( '/* testTypeUnionPropertyMulti2 */' ),
			'type for property, last of multi-union'       => array( '/* testTypeUnionPropertyMulti3 */' ),
			'type for property using namespace relative names' => array( '/* testTypeUnionPropertyNamespaceRelative */' ),
			'type for property using partially qualified names' => array( '/* testTypeUnionPropertyPartiallyQualified */' ),
			'type for property using fully qualified names' => array( '/* testTypeUnionPropertyFullyQualified */' ),
			'type for readonly property'                   => array( '/* testTypeUnionPropertyWithReadOnlyKeyword */' ),
			'type for static readonly property'            => array( '/* testTypeUnionPropertyWithStaticAndReadOnlyKeywords */' ),
			'type for readonly property using var keyword' => array( '/* testTypeUnionPropertyWithVarAndReadOnlyKeywords */' ),
			'type for readonly property, reversed modifier order' => array( '/* testTypeUnionPropertyWithReadOnlyKeywordFirst */' ),
			'type for readonly property, no visibility'    => array( '/* testTypeUnionPropertyWithOnlyReadOnlyKeyword */' ),
			'type for static property, no visibility'      => array( '/* testTypeUnionPropertyWithOnlyStaticKeyword */' ),
			'type for final property, no visibility'       => array( '/* testTypeUnionWithPHP84FinalKeyword */' ),
			'type for final property, reversed modifier order' => array( '/* testTypeUnionWithPHP84FinalKeywordFirst */' ),
			'type for final property, no visibility, FQN type' => array( '/* testTypeUnionWithPHP84FinalKeywordAndFQN */' ),
			'type for private(set) property'               => array( '/* testTypeUnionPropertyPrivateSet */' ),
			'type for public private(set) property'        => array( '/* testTypeUnionPropertyPublicPrivateSet */' ),
			'type for protected(set) property'             => array( '/* testTypeUnionPropertyProtected */' ),
			'type for public protected(set) property'      => array( '/* testTypeUnionPropertyPublicProtected */' ),
			'type for abstract property, no visibility'    => array( '/* testTypeUnionWithPHP84AbstractKeyword */' ),
			'type for abstract property, reversed modifier order' => array( '/* testTypeUnionWithPHP84AbstractKeywordFirst */' ),
			'type for abstract property, no visibility, FQN type' => array( '/* testTypeUnionWithPHP84AbstractKeywordAndFQN */' ),
			'type for method parameter'                    => array( '/* testTypeUnionParam1 */' ),
			'type for method parameter, first in multi-union' => array( '/* testTypeUnionParam2 */' ),
			'type for method parameter, last in multi-union' => array( '/* testTypeUnionParam3 */' ),
			'type for method parameter with namespace relative names' => array( '/* testTypeUnionParamNamespaceRelative */' ),
			'type for method parameter with partially qualified names' => array( '/* testTypeUnionParamPartiallyQualified */' ),
			'type for method parameter with fully qualified names' => array( '/* testTypeUnionParamFullyQualified */' ),
			'type for property in constructor property promotion' => array( '/* testTypeUnionConstructorPropertyPromotion */' ),
			'return type for method'                       => array( '/* testTypeUnionReturnType */' ),
			'return type for method, first of multi-union' => array( '/* testTypeUnionAbstractMethodReturnType1 */' ),
			'return type for method, last of multi-union'  => array( '/* testTypeUnionAbstractMethodReturnType2 */' ),
			'return type for method with namespace relative names' => array( '/* testTypeUnionReturnTypeNamespaceRelative */' ),
			'return type for method with partially qualified names' => array( '/* testTypeUnionReturnPartiallyQualified */' ),
			'return type for method with fully qualified names' => array( '/* testTypeUnionReturnFullyQualified */' ),
			'type for function parameter with reference'   => array( '/* testTypeUnionWithReference */' ),
			'type for function parameter with spread operator' => array( '/* testTypeUnionWithSpreadOperator */' ),
			'DNF type for OO constant, union before DNF'   => array( '/* testTypeUnionConstantTypeUnionBeforeDNF */' ),
			'DNF type for property, union after DNF'       => array( '/* testTypeUnionPropertyTypeUnionAfterDNF */' ),
			'DNF type for function param, union before and after DNF' => array( '/* testTypeUnionParamUnionBeforeAndAfterDNF */' ),
			'DNF type for function return, union after DNF with null' => array( '/* testTypeUnionReturnTypeUnionAfterDNF */' ),
			'type for closure parameter with illegal nullable' => array( '/* testTypeUnionClosureParamIllegalNullable */' ),
			'return type for closure'                      => array( '/* testTypeUnionClosureReturn */' ),
			'type for arrow function parameter'            => array( '/* testTypeUnionArrowParam */' ),
			'return type for arrow function'               => array( '/* testTypeUnionArrowReturnType */' ),
			'type for function parameter, return by ref'   => array( '/* testTypeUnionNonArrowFunctionDeclaration */' ),
			'type for function param with true type first' => array( '/* testTypeUnionPHP82TrueFirst */' ),
			'return type for function with true type middle' => array( '/* testTypeUnionPHP82TrueMiddle */' ),
			'return type for closure with true type last'  => array( '/* testTypeUnionPHP82TrueLast */' ),
		);
	}//end dataTypeUnion()
}//end class
