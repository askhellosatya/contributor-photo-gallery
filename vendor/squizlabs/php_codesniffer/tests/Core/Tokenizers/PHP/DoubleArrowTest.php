<?php
/**
 * Tests the retokenization of the double arrow to T_MATCH_ARROW for PHP 8.0 match structures
 * and makes sure that the tokenization of other double arrows (array, arrow function, yield)
 * is not aversely affected.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020-2021 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class DoubleArrowTest extends AbstractTokenizerTestCase {



	/**
	 * Test that "normal" double arrows are correctly tokenized as `T_DOUBLE_ARROW`.
	 *
	 * @param string $testMarker The comment prefacing the target token.
	 *
	 * @dataProvider  dataDoubleArrow
	 * @coversNothing
	 *
	 * @return void
	 */
	public function testDoubleArrow( $testMarker ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_DOUBLE_ARROW, T_MATCH_ARROW, T_FN_ARROW ) );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_DOUBLE_ARROW, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_DOUBLE_ARROW (code)' );
		$this->assertSame( 'T_DOUBLE_ARROW', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_DOUBLE_ARROW (type)' );
	}//end testDoubleArrow()


	/**
	 * Data provider.
	 *
	 * @see testDoubleArrow()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataDoubleArrow() {
		return array(
			'simple_long_array'                          => array( '/* testLongArrayArrowSimple */' ),
			'simple_short_array'                         => array( '/* testShortArrayArrowSimple */' ),
			'simple_long_list'                           => array( '/* testLongListArrowSimple */' ),
			'simple_short_list'                          => array( '/* testShortListArrowSimple */' ),
			'simple_yield'                               => array( '/* testYieldArrowSimple */' ),
			'simple_foreach'                             => array( '/* testForeachArrowSimple */' ),

			'long_array_with_match_value_1'              => array( '/* testLongArrayArrowWithNestedMatchValue1 */' ),
			'long_array_with_match_value_2'              => array( '/* testLongArrayArrowWithNestedMatchValue2 */' ),
			'short_array_with_match_value_1'             => array( '/* testShortArrayArrowWithNestedMatchValue1 */' ),
			'short_array_with_match_value_2'             => array( '/* testShortArrayArrowWithNestedMatchValue2 */' ),

			'long_array_with_match_key'                  => array( '/* testLongArrayArrowWithMatchKey */' ),
			'short_array_with_match_key'                 => array( '/* testShortArrayArrowWithMatchKey */' ),

			'long_array_in_match_body_1'                 => array( '/* testLongArrayArrowInMatchBody1 */' ),
			'long_array_in_match_body_2'                 => array( '/* testLongArrayArrowInMatchBody2 */' ),
			'long_array_in_match_body_3'                 => array( '/* testLongArrayArrowInMatchBody3 */' ),
			'short_array_in_match_body_1'                => array( '/* testShortArrayArrowInMatchBody1 */' ),
			'short_array_in_match_body_2'                => array( '/* testShortArrayArrowInMatchBody2 */' ),
			'short_array_in_match_body_3'                => array( '/* testShortArrayArrowInMatchBody3 */' ),

			'short_array_in_match_case_1'                => array( '/* testShortArrayArrowinMatchCase1 */' ),
			'short_array_in_match_case_2'                => array( '/* testShortArrayArrowinMatchCase2 */' ),
			'short_array_in_match_case_3'                => array( '/* testShortArrayArrowinMatchCase3 */' ),
			'long_array_in_match_case_4'                 => array( '/* testLongArrayArrowinMatchCase4 */' ),

			'in_complex_short_array_key_match_value'     => array( '/* testShortArrayArrowInComplexMatchValueinShortArrayKey */' ),
			'in_complex_short_array_toplevel'            => array( '/* testShortArrayArrowInComplexMatchArrayMismash */' ),
			'in_complex_short_array_value_match_value'   => array( '/* testShortArrayArrowInComplexMatchValueinShortArrayValue */' ),

			'long_list_in_match_body'                    => array( '/* testLongListArrowInMatchBody */' ),
			'long_list_in_match_case'                    => array( '/* testLongListArrowInMatchCase */' ),
			'short_list_in_match_body'                   => array( '/* testShortListArrowInMatchBody */' ),
			'short_list_in_match_case'                   => array( '/* testShortListArrowInMatchCase */' ),
			'long_list_with_match_in_key'                => array( '/* testLongListArrowWithMatchInKey */' ),
			'short_list_with_match_in_key'               => array( '/* testShortListArrowWithMatchInKey */' ),

			'long_array_with_constant_default_in_key'    => array( '/* testLongArrayArrowWithClassConstantKey */' ),
			'short_array_with_constant_default_in_key'   => array( '/* testShortArrayArrowWithClassConstantKey */' ),
			'yield_with_constant_default_in_key'         => array( '/* testYieldArrowWithClassConstantKey */' ),

			'long_array_with_default_in_key_in_match'    => array( '/* testLongArrayArrowWithClassConstantKeyNestedInMatch */' ),
			'short_array_with_default_in_key_in_match'   => array( '/* testShortArrayArrowWithClassConstantKeyNestedInMatch */' ),
			'long_array_with_default_in_key_with_match'  => array( '/* testLongArrayArrowWithClassConstantKeyWithNestedMatch */' ),
			'short_array_with_default_in_key_with_match' => array( '/* testShortArrayArrowWithClassConstantKeyWithNestedMatch */' ),
		);
	}//end dataDoubleArrow()


	/**
	 * Test that double arrows in match expressions which are the demarkation between a case and the return value
	 * are correctly tokenized as `T_MATCH_ARROW`.
	 *
	 * @param string $testMarker The comment prefacing the target token.
	 *
	 * @dataProvider dataMatchArrow
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testMatchArrow( $testMarker ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_DOUBLE_ARROW, T_MATCH_ARROW, T_FN_ARROW ) );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_MATCH_ARROW, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH_ARROW (code)' );
		$this->assertSame( 'T_MATCH_ARROW', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH_ARROW (type)' );
	}//end testMatchArrow()


	/**
	 * Data provider.
	 *
	 * @see testMatchArrow()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataMatchArrow() {
		return array(
			'single_case'                             => array( '/* testMatchArrowSimpleSingleCase */' ),
			'multi_case'                              => array( '/* testMatchArrowSimpleMultiCase */' ),
			'single_case_with_trailing_comma'         => array( '/* testMatchArrowSimpleSingleCaseWithTrailingComma */' ),
			'multi_case_with_trailing_comma'          => array( '/* testMatchArrowSimpleMultiCaseWithTrailingComma */' ),
			'match_nested_outer'                      => array( '/* testMatchArrowNestedMatchOuter */' ),
			'match_nested_inner'                      => array( '/* testMatchArrowNestedMatchInner */' ),

			'in_long_array_value_1'                   => array( '/* testMatchArrowInLongArrayValue1 */' ),
			'in_long_array_value_2'                   => array( '/* testMatchArrowInLongArrayValue2 */' ),
			'in_long_array_value_3'                   => array( '/* testMatchArrowInLongArrayValue3 */' ),
			'in_short_array_value_1'                  => array( '/* testMatchArrowInShortArrayValue1 */' ),
			'in_short_array_value_2'                  => array( '/* testMatchArrowInShortArrayValue2 */' ),
			'in_short_array_value_3'                  => array( '/* testMatchArrowInShortArrayValue3 */' ),

			'in_long_array_key_1'                     => array( '/* testMatchArrowInLongArrayKey1 */' ),
			'in_long_array_key_2'                     => array( '/* testMatchArrowInLongArrayKey2 */' ),
			'in_short_array_key_1'                    => array( '/* testMatchArrowInShortArrayKey1 */' ),
			'in_short_array_key_2'                    => array( '/* testMatchArrowInShortArrayKey2 */' ),

			'with_long_array_value_with_keys'         => array( '/* testMatchArrowWithLongArrayBodyWithKeys */' ),
			'with_short_array_value_without_keys'     => array( '/* testMatchArrowWithShortArrayBodyWithoutKeys */' ),
			'with_long_array_value_without_keys'      => array( '/* testMatchArrowWithLongArrayBodyWithoutKeys */' ),
			'with_short_array_value_with_keys'        => array( '/* testMatchArrowWithShortArrayBodyWithKeys */' ),

			'with_short_array_with_keys_as_case'      => array( '/* testMatchArrowWithShortArrayWithKeysAsCase */' ),
			'with_multiple_arrays_with_keys_as_case'  => array( '/* testMatchArrowWithMultipleArraysWithKeysAsCase */' ),

			'in_fn_body_case'                         => array( '/* testMatchArrowInFnBody1 */' ),
			'in_fn_body_default'                      => array( '/* testMatchArrowInFnBody2 */' ),
			'with_fn_body_case'                       => array( '/* testMatchArrowWithFnBody1 */' ),
			'with_fn_body_default'                    => array( '/* testMatchArrowWithFnBody2 */' ),

			'in_complex_short_array_key_1'            => array( '/* testMatchArrowInComplexShortArrayKey1 */' ),
			'in_complex_short_array_key_2'            => array( '/* testMatchArrowInComplexShortArrayKey2 */' ),
			'in_complex_short_array_value_1'          => array( '/* testMatchArrowInComplexShortArrayValue1 */' ),
			'in_complex_short_array_value_2'          => array( '/* testMatchArrowInComplexShortArrayValue2 */' ),

			'with_long_list_in_body'                  => array( '/* testMatchArrowWithLongListBody */' ),
			'with_long_list_in_case'                  => array( '/* testMatchArrowWithLongListInCase */' ),
			'with_short_list_in_body'                 => array( '/* testMatchArrowWithShortListBody */' ),
			'with_short_list_in_case'                 => array( '/* testMatchArrowWithShortListInCase */' ),
			'in_long_list_key'                        => array( '/* testMatchArrowInLongListKey */' ),
			'in_short_list_key'                       => array( '/* testMatchArrowInShortListKey */' ),

			'with_long_array_value_with_default_key'  => array( '/* testMatchArrowWithNestedLongArrayWithClassConstantKey */' ),
			'with_short_array_value_with_default_key' => array( '/* testMatchArrowWithNestedShortArrayWithClassConstantKey */' ),
			'in_long_array_value_with_default_key'    => array( '/* testMatchArrowNestedInLongArrayWithClassConstantKey */' ),
			'in_short_array_value_with_default_key'   => array( '/* testMatchArrowNestedInShortArrayWithClassConstantKey */' ),
		);
	}//end dataMatchArrow()


	/**
	 * Test that double arrows used as the scope opener for an arrow function
	 * are correctly tokenized as `T_FN_ARROW`.
	 *
	 * @param string $testMarker The comment prefacing the target token.
	 *
	 * @dataProvider dataFnArrow
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testFnArrow( $testMarker ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_DOUBLE_ARROW, T_MATCH_ARROW, T_FN_ARROW ) );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_FN_ARROW, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_FN_ARROW (code)' );
		$this->assertSame( 'T_FN_ARROW', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_FN_ARROW (type)' );
	}//end testFnArrow()


	/**
	 * Data provider.
	 *
	 * @see testFnArrow()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataFnArrow() {
		return array(
			'simple_fn'                             => array( '/* testFnArrowSimple */' ),

			'with_match_as_value'                   => array( '/* testFnArrowWithMatchInValue */' ),
			'in_match_value_case'                   => array( '/* testFnArrowInMatchBody1 */' ),
			'in_match_value_default'                => array( '/* testFnArrowInMatchBody2 */' ),

			'in_complex_match_value_in_short_array' => array( '/* testFnArrowInComplexMatchValueInShortArrayValue */' ),
		);
	}//end dataFnArrow()
}//end class
