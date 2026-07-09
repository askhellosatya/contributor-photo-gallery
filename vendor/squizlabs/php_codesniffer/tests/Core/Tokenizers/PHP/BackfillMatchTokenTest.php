<?php
/**
 * Tests the backfilling of the T_MATCH token to PHP < 8.0, as well as the
 * setting of parenthesis/scopes for match control structures across PHP versions.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020-2021 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class BackfillMatchTokenTest extends AbstractTokenizerTestCase {



	/**
	 * Test tokenization of match expressions.
	 *
	 * @param string $testMarker   The comment prefacing the target token.
	 * @param int    $openerOffset The expected offset of the scope opener in relation to the testMarker.
	 * @param int    $closerOffset The expected offset of the scope closer in relation to the testMarker.
	 * @param string $testContent  The token content to look for.
	 *
	 * @dataProvider dataMatchExpression
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testMatchExpression( $testMarker, $openerOffset, $closerOffset, $testContent = 'match' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_STRING, T_MATCH ), $testContent );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_MATCH, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH (code)' );
		$this->assertSame( 'T_MATCH', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_MATCH (type)' );

		$this->scopeTestHelper( $token, $openerOffset, $closerOffset );
		$this->parenthesisTestHelper( $token );
	}//end testMatchExpression()


	/**
	 * Data provider.
	 *
	 * @see testMatchExpression()
	 *
	 * @return array<string, array<string, string|int>>
	 */
	public static function dataMatchExpression() {
		return array(
			'simple_match'                              => array(
				'testMarker'   => '/* testMatchSimple */',
				'openerOffset' => 6,
				'closerOffset' => 33,
			),
			'no_trailing_comma'                         => array(
				'testMarker'   => '/* testMatchNoTrailingComma */',
				'openerOffset' => 6,
				'closerOffset' => 24,
			),
			'with_default_case'                         => array(
				'testMarker'   => '/* testMatchWithDefault */',
				'openerOffset' => 6,
				'closerOffset' => 33,
			),
			'expression_in_condition'                   => array(
				'testMarker'   => '/* testMatchExpressionInCondition */',
				'openerOffset' => 6,
				'closerOffset' => 77,
			),
			'multicase'                                 => array(
				'testMarker'   => '/* testMatchMultiCase */',
				'openerOffset' => 6,
				'closerOffset' => 40,
			),
			'multicase_trailing_comma_in_case'          => array(
				'testMarker'   => '/* testMatchMultiCaseTrailingCommaInCase */',
				'openerOffset' => 6,
				'closerOffset' => 47,
			),
			'in_closure_not_lowercase'                  => array(
				'testMarker'   => '/* testMatchInClosureNotLowercase */',
				'openerOffset' => 6,
				'closerOffset' => 36,
				'testContent'  => 'Match',
			),
			'in_arrow_function'                         => array(
				'testMarker'   => '/* testMatchInArrowFunction */',
				'openerOffset' => 5,
				'closerOffset' => 36,
			),
			'arrow_function_in_match_no_trailing_comma' => array(
				'testMarker'   => '/* testArrowFunctionInMatchNoTrailingComma */',
				'openerOffset' => 6,
				'closerOffset' => 44,
			),
			'in_function_call_param_not_lowercase'      => array(
				'testMarker'   => '/* testMatchInFunctionCallParamNotLowercase */',
				'openerOffset' => 8,
				'closerOffset' => 32,
				'testContent'  => 'MATCH',
			),
			'in_method_call_param'                      => array(
				'testMarker'   => '/* testMatchInMethodCallParam */',
				'openerOffset' => 5,
				'closerOffset' => 13,
			),
			'discard_result'                            => array(
				'testMarker'   => '/* testMatchDiscardResult */',
				'openerOffset' => 6,
				'closerOffset' => 18,
			),
			'duplicate_conditions_and_comments'         => array(
				'testMarker'   => '/* testMatchWithDuplicateConditionsWithComments */',
				'openerOffset' => 12,
				'closerOffset' => 59,
			),
			'nested_match_outer'                        => array(
				'testMarker'   => '/* testNestedMatchOuter */',
				'openerOffset' => 6,
				'closerOffset' => 33,
			),
			'nested_match_inner'                        => array(
				'testMarker'   => '/* testNestedMatchInner */',
				'openerOffset' => 6,
				'closerOffset' => 14,
			),
			'ternary_condition'                         => array(
				'testMarker'   => '/* testMatchInTernaryCondition */',
				'openerOffset' => 6,
				'closerOffset' => 21,
			),
			'ternary_then'                              => array(
				'testMarker'   => '/* testMatchInTernaryThen */',
				'openerOffset' => 6,
				'closerOffset' => 21,
			),
			'ternary_else'                              => array(
				'testMarker'   => '/* testMatchInTernaryElse */',
				'openerOffset' => 6,
				'closerOffset' => 21,
			),
			'array_value'                               => array(
				'testMarker'   => '/* testMatchInArrayValue */',
				'openerOffset' => 6,
				'closerOffset' => 21,
			),
			'array_key'                                 => array(
				'testMarker'   => '/* testMatchInArrayKey */',
				'openerOffset' => 6,
				'closerOffset' => 21,
			),
			'returning_array'                           => array(
				'testMarker'   => '/* testMatchreturningArray */',
				'openerOffset' => 6,
				'closerOffset' => 125,
			),
			'nested_in_switch_case_1'                   => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchCase1 */',
				'openerOffset' => 6,
				'closerOffset' => 25,
			),
			'nested_in_switch_case_2'                   => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchCase2 */',
				'openerOffset' => 6,
				'closerOffset' => 25,
			),
			'nested_in_switch_default'                  => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchDefault */',
				'openerOffset' => 6,
				'closerOffset' => 25,
			),
			'match_with_nested_switch'                  => array(
				'testMarker'   => '/* testMatchContainingSwitch */',
				'openerOffset' => 6,
				'closerOffset' => 180,
			),
			'no_cases'                                  => array(
				'testMarker'   => '/* testMatchNoCases */',
				'openerOffset' => 6,
				'closerOffset' => 7,
			),
			'multi_default'                             => array(
				'testMarker'   => '/* testMatchMultiDefault */',
				'openerOffset' => 6,
				'closerOffset' => 40,
			),
		);
	}//end dataMatchExpression()


	/**
	 * Verify that "match" keywords which are not match control structures get tokenized as T_STRING
	 * and don't have the extra token array indexes.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataNotAMatchStructure
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testNotAMatchStructure( $testMarker, $testContent = 'match' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_STRING, T_MATCH ), $testContent );
		$tokenArray = $tokens[ $token ];

		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );
		$this->assertSame( 'T_STRING', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (type)' );

		$this->assertArrayNotHasKey( 'scope_condition', $tokenArray, 'Scope condition is set' );
		$this->assertArrayNotHasKey( 'scope_opener', $tokenArray, 'Scope opener is set' );
		$this->assertArrayNotHasKey( 'scope_closer', $tokenArray, 'Scope closer is set' );
		$this->assertArrayNotHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is set' );
		$this->assertArrayNotHasKey( 'parenthesis_opener', $tokenArray, 'Parenthesis opener is set' );
		$this->assertArrayNotHasKey( 'parenthesis_closer', $tokenArray, 'Parenthesis closer is set' );

		$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $token + 1 ), null, true );
		if ( $next !== false && $tokens[ $next ]['code'] === T_OPEN_PARENTHESIS ) {
			$this->assertArrayNotHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is set for opener after' );
		}
	}//end testNotAMatchStructure()


	/**
	 * Data provider.
	 *
	 * @see testNotAMatchStructure()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataNotAMatchStructure() {
		return array(
			'static_method_call'                   => array(
				'testMarker' => '/* testNoMatchStaticMethodCall */',
			),
			'class_constant_access'                => array(
				'testMarker'  => '/* testNoMatchClassConstantAccess */',
				'testContent' => 'MATCH',
			),
			'class_constant_array_access'          => array(
				'testMarker'  => '/* testNoMatchClassConstantArrayAccessMixedCase */',
				'testContent' => 'Match',
			),
			'method_call'                          => array(
				'testMarker' => '/* testNoMatchMethodCall */',
			),
			'method_call_uppercase'                => array(
				'testMarker'  => '/* testNoMatchMethodCallUpper */',
				'testContent' => 'MATCH',
			),
			'property_access'                      => array(
				'testMarker' => '/* testNoMatchPropertyAccess */',
			),
			'namespaced_function_call'             => array(
				'testMarker' => '/* testNoMatchNamespacedFunctionCall */',
			),
			'namespace_operator_function_call'     => array(
				'testMarker' => '/* testNoMatchNamespaceOperatorFunctionCall */',
			),
			'interface_method_declaration'         => array(
				'testMarker' => '/* testNoMatchInterfaceMethodDeclaration */',
			),
			'class_constant_declaration'           => array(
				'testMarker' => '/* testNoMatchClassConstantDeclarationLower */',
			),
			'class_method_declaration'             => array(
				'testMarker' => '/* testNoMatchClassMethodDeclaration */',
			),
			'property_assigment'                   => array(
				'testMarker' => '/* testNoMatchPropertyAssignment */',
			),
			'class_instantiation'                  => array(
				'testMarker'  => '/* testNoMatchClassInstantiation */',
				'testContent' => 'Match',
			),
			'anon_class_method_declaration'        => array(
				'testMarker'  => '/* testNoMatchAnonClassMethodDeclaration */',
				'testContent' => 'maTCH',
			),
			'class_declaration'                    => array(
				'testMarker'  => '/* testNoMatchClassDeclaration */',
				'testContent' => 'Match',
			),
			'interface_declaration'                => array(
				'testMarker'  => '/* testNoMatchInterfaceDeclaration */',
				'testContent' => 'Match',
			),
			'trait_declaration'                    => array(
				'testMarker'  => '/* testNoMatchTraitDeclaration */',
				'testContent' => 'Match',
			),
			'constant_declaration'                 => array(
				'testMarker'  => '/* testNoMatchConstantDeclaration */',
				'testContent' => 'MATCH',
			),
			'function_declaration'                 => array(
				'testMarker' => '/* testNoMatchFunctionDeclaration */',
			),
			'namespace_declaration'                => array(
				'testMarker'  => '/* testNoMatchNamespaceDeclaration */',
				'testContent' => 'Match',
			),
			'class_extends_declaration'            => array(
				'testMarker'  => '/* testNoMatchExtendedClassDeclaration */',
				'testContent' => 'Match',
			),
			'class_implements_declaration'         => array(
				'testMarker'  => '/* testNoMatchImplementedClassDeclaration */',
				'testContent' => 'Match',
			),
			'use_statement'                        => array(
				'testMarker'  => '/* testNoMatchInUseStatement */',
				'testContent' => 'Match',
			),
			'unsupported_inline_control_structure' => array(
				'testMarker' => '/* testNoMatchMissingCurlies */',
			),
			'unsupported_alternative_syntax'       => array(
				'testMarker' => '/* testNoMatchAlternativeSyntax */',
			),
			'live_coding'                          => array(
				'testMarker' => '/* testLiveCoding */',
			),
		);
	}//end dataNotAMatchStructure()


	/**
	 * Verify that the tokenization of switch structures is not affected by the backfill.
	 *
	 * @param string $testMarker   The comment prefacing the target token.
	 * @param int    $openerOffset The expected offset of the scope opener in relation to the testMarker.
	 * @param int    $closerOffset The expected offset of the scope closer in relation to the testMarker.
	 *
	 * @dataProvider dataSwitchExpression
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testSwitchExpression( $testMarker, $openerOffset, $closerOffset ) {
		$token = $this->getTargetToken( $testMarker, T_SWITCH );

		$this->scopeTestHelper( $token, $openerOffset, $closerOffset );
		$this->parenthesisTestHelper( $token );
	}//end testSwitchExpression()


	/**
	 * Data provider.
	 *
	 * @see testSwitchExpression()
	 *
	 * @return array<string, array<string, string|int>>
	 */
	public static function dataSwitchExpression() {
		return array(
			'switch_containing_match'   => array(
				'testMarker'   => '/* testSwitchContainingMatch */',
				'openerOffset' => 6,
				'closerOffset' => 174,
			),
			'match_containing_switch_1' => array(
				'testMarker'   => '/* testSwitchNestedInMatch1 */',
				'openerOffset' => 5,
				'closerOffset' => 63,
			),
			'match_containing_switch_2' => array(
				'testMarker'   => '/* testSwitchNestedInMatch2 */',
				'openerOffset' => 5,
				'closerOffset' => 63,
			),
		);
	}//end dataSwitchExpression()


	/**
	 * Verify that the tokenization of a switch case/default structure containing a match structure
	 * or contained *in* a match structure is not affected by the backfill.
	 *
	 * @param string $testMarker   The comment prefacing the target token.
	 * @param int    $openerOffset The expected offset of the scope opener in relation to the testMarker.
	 * @param int    $closerOffset The expected offset of the scope closer in relation to the testMarker.
	 *
	 * @dataProvider dataSwitchCaseVersusMatch
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testSwitchCaseVersusMatch( $testMarker, $openerOffset, $closerOffset ) {
		$token = $this->getTargetToken( $testMarker, array( T_CASE, T_DEFAULT ) );

		$this->scopeTestHelper( $token, $openerOffset, $closerOffset );
	}//end testSwitchCaseVersusMatch()


	/**
	 * Data provider.
	 *
	 * @see testSwitchCaseVersusMatch()
	 *
	 * @return array<string, array<string, string|int>>
	 */
	public static function dataSwitchCaseVersusMatch() {
		return array(
			'switch_with_nested_match_case_1'       => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchCase1 */',
				'openerOffset' => 3,
				'closerOffset' => 55,
			),
			'switch_with_nested_match_case_2'       => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchCase2 */',
				'openerOffset' => 4,
				'closerOffset' => 21,
			),
			'switch_with_nested_match_default_case' => array(
				'testMarker'   => '/* testMatchWithDefaultNestedInSwitchDefault */',
				'openerOffset' => 1,
				'closerOffset' => 38,
			),
			'match_with_nested_switch_case'         => array(
				'testMarker'   => '/* testSwitchDefaultNestedInMatchCase */',
				'openerOffset' => 1,
				'closerOffset' => 18,
			),
			'match_with_nested_switch_default_case' => array(
				'testMarker'   => '/* testSwitchDefaultNestedInMatchDefault */',
				'openerOffset' => 1,
				'closerOffset' => 20,
			),
		);
	}//end dataSwitchCaseVersusMatch()


	/**
	 * Helper function to verify that all scope related array indexes for a control structure
	 * are set correctly.
	 *
	 * @param int  $token                The control structure token to check.
	 * @param int  $openerOffset         The expected offset of the scope opener in relation to
	 *                                   the control structure token.
	 * @param int  $closerOffset         The expected offset of the scope closer in relation to
	 *                                   the control structure token.
	 * @param bool $skipScopeCloserCheck Whether to skip the scope closer check.
	 *                                   This should be set to "true" when testing nested arrow functions,
	 *                                   where the "inner" arrow function shares a scope closer with the
	 *                                   "outer" arrow function, as the 'scope_condition' for the scope closer
	 *                                   of the "inner" arrow function will point to the "outer" arrow function.
	 *
	 * @return void
	 */
	private function scopeTestHelper( $token, $openerOffset, $closerOffset, $skipScopeCloserCheck = false ) {
		$tokens              = $this->phpcsFile->getTokens();
		$tokenArray          = $tokens[ $token ];
		$tokenType           = $tokenArray['type'];
		$expectedScopeOpener = ( $token + $openerOffset );
		$expectedScopeCloser = ( $token + $closerOffset );

		$this->assertArrayHasKey( 'scope_condition', $tokenArray, 'Scope condition is not set' );
		$this->assertArrayHasKey( 'scope_opener', $tokenArray, 'Scope opener is not set' );
		$this->assertArrayHasKey( 'scope_closer', $tokenArray, 'Scope closer is not set' );
		$this->assertSame( $token, $tokenArray['scope_condition'], 'Scope condition is not the ' . $tokenType . ' token' );
		$this->assertSame( $expectedScopeOpener, $tokenArray['scope_opener'], 'Scope opener of the ' . $tokenType . ' token incorrect' );
		$this->assertSame( $expectedScopeCloser, $tokenArray['scope_closer'], 'Scope closer of the ' . $tokenType . ' token incorrect' );

		$opener = $tokenArray['scope_opener'];
		$this->assertArrayHasKey( 'scope_condition', $tokens[ $opener ], 'Opener scope condition is not set' );
		$this->assertArrayHasKey( 'scope_opener', $tokens[ $opener ], 'Opener scope opener is not set' );
		$this->assertArrayHasKey( 'scope_closer', $tokens[ $opener ], 'Opener scope closer is not set' );
		$this->assertSame( $token, $tokens[ $opener ]['scope_condition'], 'Opener scope condition is not the ' . $tokenType . ' token' );
		$this->assertSame( $expectedScopeOpener, $tokens[ $opener ]['scope_opener'], $tokenType . ' opener scope opener token incorrect' );
		$this->assertSame( $expectedScopeCloser, $tokens[ $opener ]['scope_closer'], $tokenType . ' opener scope closer token incorrect' );

		$closer = $tokenArray['scope_closer'];
		$this->assertArrayHasKey( 'scope_condition', $tokens[ $closer ], 'Closer scope condition is not set' );
		$this->assertArrayHasKey( 'scope_opener', $tokens[ $closer ], 'Closer scope opener is not set' );
		$this->assertArrayHasKey( 'scope_closer', $tokens[ $closer ], 'Closer scope closer is not set' );
		if ( $skipScopeCloserCheck === false ) {
			$this->assertSame( $token, $tokens[ $closer ]['scope_condition'], 'Closer scope condition is not the ' . $tokenType . ' token' );
		}

		$this->assertSame( $expectedScopeOpener, $tokens[ $closer ]['scope_opener'], $tokenType . ' closer scope opener token incorrect' );
		$this->assertSame( $expectedScopeCloser, $tokens[ $closer ]['scope_closer'], $tokenType . ' closer scope closer token incorrect' );

		if ( ( $opener + 1 ) !== $closer ) {
			for ( $i = ( $opener + 1 ); $i < $closer; $i++ ) {
				$this->assertArrayHasKey(
					$token,
					$tokens[ $i ]['conditions'],
					$tokenType . ' condition not added for token belonging to the ' . $tokenType . ' structure'
				);
			}
		}
	}//end scopeTestHelper()


	/**
	 * Helper function to verify that all parenthesis related array indexes for a control structure
	 * token are set correctly.
	 *
	 * @param int $token The position of the control structure token.
	 *
	 * @return void
	 */
	private function parenthesisTestHelper( $token ) {
		$tokens     = $this->phpcsFile->getTokens();
		$tokenArray = $tokens[ $token ];
		$tokenType  = $tokenArray['type'];

		$this->assertArrayHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is not set' );
		$this->assertArrayHasKey( 'parenthesis_opener', $tokenArray, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $tokenArray, 'Parenthesis closer is not set' );
		$this->assertSame( $token, $tokenArray['parenthesis_owner'], 'Parenthesis owner is not the ' . $tokenType . ' token' );

		$opener = $tokenArray['parenthesis_opener'];
		$this->assertArrayHasKey( 'parenthesis_owner', $tokens[ $opener ], 'Opening parenthesis owner is not set' );
		$this->assertSame( $token, $tokens[ $opener ]['parenthesis_owner'], 'Opening parenthesis owner is not the ' . $tokenType . ' token' );

		$closer = $tokenArray['parenthesis_closer'];
		$this->assertArrayHasKey( 'parenthesis_owner', $tokens[ $closer ], 'Closing parenthesis owner is not set' );
		$this->assertSame( $token, $tokens[ $closer ]['parenthesis_owner'], 'Closing parenthesis owner is not the ' . $tokenType . ' token' );
	}//end parenthesisTestHelper()
}//end class
