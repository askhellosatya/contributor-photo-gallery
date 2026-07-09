<?php
/**
 * Tests the retokenization of the `default` keyword to T_MATCH_DEFAULT for PHP 8.0 match structures
 * and makes sure that the tokenization of switch `T_DEFAULT` structures is not aversely affected.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020-2021 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class DefaultKeywordTest extends AbstractTokenizerTestCase {



	/**
	 * Test the retokenization of the `default` keyword for match structure to `T_MATCH_DEFAULT`.
	 *
	 * Note: Cases and default structures within a match structure do *NOT* get case/default scope
	 * conditions, in contrast to case and default structures in switch control structures.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataMatchDefault
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testMatchDefault( $testMarker, $testContent = 'default' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_MATCH_DEFAULT, T_DEFAULT, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_MATCH_DEFAULT, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH_DEFAULT (code)' );
		$this->assertSame( 'T_MATCH_DEFAULT', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH_DEFAULT (type)' );
	}//end testMatchDefault()


	/**
	 * Data provider.
	 *
	 * @see testMatchDefault()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataMatchDefault() {
		return array(
			'simple_match_default'            => array(
				'testMarker' => '/* testSimpleMatchDefault */',
			),
			'match_default_in_switch_case_1'  => array(
				'testMarker' => '/* testMatchDefaultNestedInSwitchCase1 */',
			),
			'match_default_in_switch_case_2'  => array(
				'testMarker' => '/* testMatchDefaultNestedInSwitchCase2 */',
			),
			'match_default_in_switch_default' => array(
				'testMarker' => '/* testMatchDefaultNestedInSwitchDefault */',
			),
			'match_default_containing_switch' => array(
				'testMarker' => '/* testMatchDefault */',
			),

			'match_default_with_nested_long_array_and_default_key' => array(
				'testMarker'  => '/* testMatchDefaultWithNestedLongArrayWithClassConstantKey */',
				'testContent' => 'DEFAULT',
			),
			'match_default_with_nested_long_array_and_default_key_2' => array(
				'testMarker'  => '/* testMatchDefaultWithNestedLongArrayWithClassConstantKeyLevelDown */',
				'testContent' => 'DEFAULT',
			),
			'match_default_with_nested_short_array_and_default_key' => array(
				'testMarker'  => '/* testMatchDefaultWithNestedShortArrayWithClassConstantKey */',
				'testContent' => 'DEFAULT',
			),
			'match_default_with_nested_short_array_and_default_key_2' => array(
				'testMarker'  => '/* testMatchDefaultWithNestedShortArrayWithClassConstantKeyLevelDown */',
				'testContent' => 'DEFAULT',
			),
			'match_default_in_long_array'     => array(
				'testMarker'  => '/* testMatchDefaultNestedInLongArray */',
				'testContent' => 'DEFAULT',
			),
			'match_default_in_short_array'    => array(
				'testMarker'  => '/* testMatchDefaultNestedInShortArray */',
				'testContent' => 'DEFAULT',
			),
		);
	}//end dataMatchDefault()


	/**
	 * Verify that the retokenization of `T_DEFAULT` tokens in match constructs, doesn't negatively
	 * impact the tokenization of `T_DEFAULT` tokens in switch control structures.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataSwitchDefault
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testSwitchDefault( $testMarker, $testContent = 'default' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_MATCH_DEFAULT, T_DEFAULT, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_DEFAULT, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_DEFAULT (code)' );
		$this->assertSame( 'T_DEFAULT', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_DEFAULT (type)' );
	}//end testSwitchDefault()


	/**
	 * Data provider.
	 *
	 * @see testSwitchDefault()
	 *
	 * @return array<string, array<string, string|int>>
	 */
	public static function dataSwitchDefault() {
		return array(
			'simple_switch_default'                  => array(
				'testMarker' => '/* testSimpleSwitchDefault */',
			),
			'simple_switch_default_with_curlies'     => array(
				'testMarker' => '/* testSimpleSwitchDefaultWithCurlies */',
			),
			'switch_default_toplevel'                => array(
				'testMarker' => '/* testSwitchDefault */',
			),
			'switch_default_nested_in_match_case'    => array(
				'testMarker' => '/* testSwitchDefaultNestedInMatchCase */',
			),
			'switch_default_nested_in_match_default' => array(
				'testMarker' => '/* testSwitchDefaultNestedInMatchDefault */',
			),
		);
	}//end dataSwitchDefault()


	/**
	 * Verify that the retokenization of `T_DEFAULT` tokens in match constructs, doesn't negatively
	 * impact the tokenization of `T_STRING` tokens with the contents 'default' which aren't in
	 * actual fact the default keyword.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataNotDefaultKeyword
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testNotDefaultKeyword( $testMarker, $testContent = 'DEFAULT' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_MATCH_DEFAULT, T_DEFAULT, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );
		$this->assertSame( 'T_STRING', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (type)' );
	}//end testNotDefaultKeyword()


	/**
	 * Data provider.
	 *
	 * @see testNotDefaultKeyword()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataNotDefaultKeyword() {
		return array(
			'class-constant-as-short-array-key'          => array(
				'testMarker' => '/* testClassConstantAsShortArrayKey */',
			),
			'class-property-as-short-array-key'          => array(
				'testMarker' => '/* testClassPropertyAsShortArrayKey */',
			),
			'namespaced-constant-as-short-array-key'     => array(
				'testMarker' => '/* testNamespacedConstantAsShortArrayKey */',
			),
			'fqn-global-constant-as-short-array-key'     => array(
				'testMarker' => '/* testFQNGlobalConstantAsShortArrayKey */',
			),
			'class-constant-as-long-array-key'           => array(
				'testMarker' => '/* testClassConstantAsLongArrayKey */',
			),
			'class-constant-as-yield-key'                => array(
				'testMarker' => '/* testClassConstantAsYieldKey */',
			),

			'class-constant-as-long-array-key-nested-in-match' => array(
				'testMarker' => '/* testClassConstantAsLongArrayKeyNestedInMatch */',
			),
			'class-constant-as-long-array-key-nested-in-match-2' => array(
				'testMarker' => '/* testClassConstantAsLongArrayKeyNestedInMatchLevelDown */',
			),
			'class-constant-as-short-array-key-nested-in-match' => array(
				'testMarker' => '/* testClassConstantAsShortArrayKeyNestedInMatch */',
			),
			'class-constant-as-short-array-key-nested-in-match-2' => array(
				'testMarker' => '/* testClassConstantAsShortArrayKeyNestedInMatchLevelDown */',
			),
			'class-constant-as-long-array-key-with-nested-match' => array(
				'testMarker' => '/* testClassConstantAsLongArrayKeyWithNestedMatch */',
			),
			'class-constant-as-short-array-key-with-nested-match' => array(
				'testMarker' => '/* testClassConstantAsShortArrayKeyWithNestedMatch */',
			),

			'class-constant-in-switch-case'              => array(
				'testMarker' => '/* testClassConstantInSwitchCase */',
			),
			'class-property-in-switch-case'              => array(
				'testMarker' => '/* testClassPropertyInSwitchCase */',
			),
			'namespaced-constant-in-switch-case'         => array(
				'testMarker' => '/* testNamespacedConstantInSwitchCase */',
			),
			'namespace-relative-constant-in-switch-case' => array(
				'testMarker' => '/* testNamespaceRelativeConstantInSwitchCase */',
			),

			'class-constant-declaration'                 => array(
				'testMarker' => '/* testClassConstant */',
			),
			'class-method-declaration'                   => array(
				'testMarker'  => '/* testMethodDeclaration */',
				'testContent' => 'default',
			),
		);
	}//end dataNotDefaultKeyword()
}//end class
