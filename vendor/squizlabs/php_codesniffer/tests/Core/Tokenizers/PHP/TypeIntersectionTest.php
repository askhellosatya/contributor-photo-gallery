<?php
/**
 * Tests the conversion of bitwise and tokens to type intersection tokens.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @author    Jaroslav Hanslík <kukulich@kukulich.cz>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class TypeIntersectionTest extends AbstractTokenizerTestCase {



	/**
	 * Test that non-intersection type bitwise and tokens are still tokenized as bitwise and.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataBitwiseAnd
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testBitwiseAnd( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_BITWISE_AND, T_TYPE_INTERSECTION ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_BITWISE_AND, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_BITWISE_AND (code)' );
		$this->assertSame( 'T_BITWISE_AND', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_BITWISE_AND (type)' );
	}//end testBitwiseAnd()


	/**
	 * Data provider.
	 *
	 * @see testBitwiseAnd()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataBitwiseAnd() {
		return array(
			'in simple assignment 1'                     => array( '/* testBitwiseAnd1 */' ),
			'in simple assignment 2'                     => array( '/* testBitwiseAnd2 */' ),
			'in OO constant default value'               => array( '/* testBitwiseAndOOConstDefaultValue */' ),
			'in property default value'                  => array( '/* testBitwiseAndPropertyDefaultValue */' ),
			'in method parameter default value'          => array( '/* testBitwiseAndParamDefaultValue */' ),
			'reference for method parameter'             => array( '/* testBitwiseAnd3 */' ),
			'in return statement'                        => array( '/* testBitwiseAnd4 */' ),
			'reference for function parameter'           => array( '/* testBitwiseAnd5 */' ),
			'in OO constant default value DNF-like'      => array( '/* testBitwiseAndOOConstDefaultValueDNF */' ),
			'in property default value DNF-like'         => array( '/* testBitwiseAndPropertyDefaultValueDNF */' ),
			'in method parameter default value DNF-like' => array( '/* testBitwiseAndParamDefaultValueDNF */' ),
			'in closure parameter default value'         => array( '/* testBitwiseAndClosureParamDefault */' ),
			'in arrow function parameter default value'  => array( '/* testBitwiseAndArrowParamDefault */' ),
			'in arrow function return expression'        => array( '/* testBitwiseAndArrowExpression */' ),
			'in long array key'                          => array( '/* testBitwiseAndInArrayKey */' ),
			'in long array value'                        => array( '/* testBitwiseAndInArrayValue */' ),
			'in short array key'                         => array( '/* testBitwiseAndInShortArrayKey */' ),
			'in short array value'                       => array( '/* testBitwiseAndInShortArrayValue */' ),
			'in parameter in function call'              => array( '/* testBitwiseAndNonArrowFnFunctionCall */' ),
			'function return by reference'               => array( '/* testBitwiseAnd6 */' ),
			'live coding / undetermined'                 => array( '/* testLiveCoding */' ),
		);
	}//end dataBitwiseAnd()


	/**
	 * Test that bitwise and tokens when used as part of a intersection type are tokenized as `T_TYPE_INTERSECTION`.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataTypeIntersection
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testTypeIntersection( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_BITWISE_AND, T_TYPE_INTERSECTION ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_TYPE_INTERSECTION, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_TYPE_INTERSECTION (code)' );
		$this->assertSame( 'T_TYPE_INTERSECTION', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_TYPE_INTERSECTION (type)' );
	}//end testTypeIntersection()


	/**
	 * Data provider.
	 *
	 * @see testTypeIntersection()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataTypeIntersection() {
		return array(
			'type for OO constant'                        => array( '/* testTypeIntersectionOOConstSimple */' ),
			'type for OO constant, reversed modifier order' => array( '/* testTypeIntersectionOOConstReverseModifierOrder */' ),
			'type for OO constant, first of multi-intersect' => array( '/* testTypeIntersectionOOConstMulti1 */' ),
			'type for OO constant, middle of multi-intersect + comments' => array( '/* testTypeIntersectionOOConstMulti2 */' ),
			'type for OO constant, last of multi-intersect' => array( '/* testTypeIntersectionOOConstMulti3 */' ),
			'type for OO constant, using namespace relative names' => array( '/* testTypeIntersectionOOConstNamespaceRelative */' ),
			'type for OO constant, using partially qualified names' => array( '/* testTypeIntersectionOOConstPartiallyQualified */' ),
			'type for OO constant, using fully qualified names' => array( '/* testTypeIntersectionOOConstFullyQualified */' ),
			'type for static property'                    => array( '/* testTypeIntersectionPropertySimple */' ),
			'type for static property, reversed modifier order' => array( '/* testTypeIntersectionPropertyReverseModifierOrder */' ),
			'type for property, first of multi-intersect' => array( '/* testTypeIntersectionPropertyMulti1 */' ),
			'type for property, middle of multi-intersect, also comments' => array( '/* testTypeIntersectionPropertyMulti2 */' ),
			'type for property, last of multi-intersect'  => array( '/* testTypeIntersectionPropertyMulti3 */' ),
			'type for property using namespace relative names' => array( '/* testTypeIntersectionPropertyNamespaceRelative */' ),
			'type for property using partially qualified names' => array( '/* testTypeIntersectionPropertyPartiallyQualified */' ),
			'type for property using fully qualified names' => array( '/* testTypeIntersectionPropertyFullyQualified */' ),
			'type for readonly property'                  => array( '/* testTypeIntersectionPropertyWithReadOnlyKeyword */' ),
			'type for static readonly property'           => array( '/* testTypeIntersectionPropertyWithStaticKeyword */' ),
			'type for final property'                     => array( '/* testTypeIntersectionWithPHP84FinalKeyword */' ),
			'type for final property reversed modifier order' => array( '/* testTypeIntersectionWithPHP84FinalKeywordFirst */' ),
			'type for asymmetric visibility (private(set)) property' => array( '/* testTypeIntersectionPropertyWithPrivateSet */' ),
			'type for asymmetric visibility (public private(set)) prop' => array( '/* testTypeIntersectionPropertyWithPublicPrivateSet */' ),
			'type for asymmetric visibility (protected(set)) property' => array( '/* testTypeIntersectionPropertyWithProtectedSet */' ),
			'type for asymmetric visibility (public protected(set)) prop' => array( '/* testTypeIntersectionPropertyWithPublicProtectedSet */' ),
			'type for abstract property'                  => array( '/* testTypeIntersectionWithPHP84AbstractKeyword */' ),
			'type for abstract property reversed modifier order' => array( '/* testTypeIntersectionWithPHP84AbstractKeywordFirst */' ),
			'type for method parameter'                   => array( '/* testTypeIntersectionParam1 */' ),
			'type for method parameter, first in multi-intersect' => array( '/* testTypeIntersectionParam2 */' ),
			'type for method parameter, last in multi-intersect' => array( '/* testTypeIntersectionParam3 */' ),
			'type for method parameter with namespace relative names' => array( '/* testTypeIntersectionParamNamespaceRelative */' ),
			'type for method parameter with partially qualified names' => array( '/* testTypeIntersectionParamPartiallyQualified */' ),
			'type for method parameter with fully qualified names' => array( '/* testTypeIntersectionParamFullyQualified */' ),
			'type for property in constructor property promotion' => array( '/* testTypeIntersectionConstructorPropertyPromotion */' ),
			'return type for method'                      => array( '/* testTypeIntersectionReturnType */' ),
			'return type for method, first of multi-intersect' => array( '/* testTypeIntersectionAbstractMethodReturnType1 */' ),
			'return type for method, last of multi-intersect' => array( '/* testTypeIntersectionAbstractMethodReturnType2 */' ),
			'return type for method with namespace relative names' => array( '/* testTypeIntersectionReturnTypeNamespaceRelative */' ),
			'return type for method with partially qualified names' => array( '/* testTypeIntersectionReturnPartiallyQualified */' ),
			'return type for method with fully qualified names' => array( '/* testTypeIntersectionReturnFullyQualified */' ),
			'type for function parameter with reference'  => array( '/* testTypeIntersectionWithReference */' ),
			'type for function parameter with spread operator' => array( '/* testTypeIntersectionWithSpreadOperator */' ),
			'DNF type for OO constant, union before DNF'  => array( '/* testTypeIntersectionConstantTypeUnionBeforeDNF */' ),
			'DNF type for property, union after DNF'      => array( '/* testTypeIntersectionPropertyTypeUnionAfterDNF */' ),
			'DNF type for function param, union before and after DNF' => array( '/* testTypeIntersectionParamUnionBeforeAndAfterDNF */' ),
			'DNF type for function return, union after DNF with null' => array( '/* testTypeIntersectionReturnTypeUnionAfterDNF */' ),
			'type for closure parameter with illegal nullable' => array( '/* testTypeIntersectionClosureParamIllegalNullable */' ),
			'return type for closure'                     => array( '/* testTypeIntersectionClosureReturn */' ),
			'type for arrow function parameter'           => array( '/* testTypeIntersectionArrowParam */' ),
			'return type for arrow function'              => array( '/* testTypeIntersectionArrowReturnType */' ),
			'type for function parameter, return by ref'  => array( '/* testTypeIntersectionNonArrowFunctionDeclaration */' ),
			'type for function parameter with invalid types' => array( '/* testTypeIntersectionWithInvalidTypes */' ),
		);
	}//end dataTypeIntersection()
}//end class
