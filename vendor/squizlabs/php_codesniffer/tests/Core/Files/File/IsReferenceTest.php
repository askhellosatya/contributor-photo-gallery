<?php
/**
 * Tests for the \PHP_CodeSniffer\Files\File::isReference method.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Files\File;

use PHP_CodeSniffer\Tests\Core\AbstractMethodUnitTest;

/**
 * Tests for the \PHP_CodeSniffer\Files\File::isReference method.
 *
 * @covers \PHP_CodeSniffer\Files\File::isReference
 */
final class IsReferenceTest extends AbstractMethodUnitTest {



	/**
	 * Test that false is returned when a non-"bitwise and" token is passed.
	 *
	 * @param string            $testMarker   Comment which precedes the test case.
	 * @param array<int|string> $targetTokens Type of tokens to look for.
	 *
	 * @dataProvider dataNotBitwiseAndToken
	 *
	 * @return void
	 */
	public function testNotBitwiseAndToken( $testMarker, $targetTokens ) {
		$targetTokens[] = T_BITWISE_AND;

		$target = $this->getTargetToken( $testMarker, $targetTokens );
		$this->assertFalse( self::$phpcsFile->isReference( $target ) );
	}//end testNotBitwiseAndToken()


	/**
	 * Data provider.
	 *
	 * @see testNotBitwiseAndToken()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataNotBitwiseAndToken() {
		return array(
			'Not ampersand token at all'     => array(
				'testMarker'   => '/* testBitwiseAndA */',
				'targetTokens' => array( T_STRING ),
			),
			'ampersand in intersection type' => array(
				'testMarker'   => '/* testIntersectionIsNotReference */',
				'targetTokens' => array( T_TYPE_INTERSECTION ),
			),
			'ampersand in DNF type'          => array(
				'testMarker'   => '/* testDNFTypeIsNotReference */',
				'targetTokens' => array( T_TYPE_INTERSECTION ),
			),
		);
	}//end dataNotBitwiseAndToken()


	/**
	 * Test correctly identifying whether a "bitwise and" token is a reference or not.
	 *
	 * @param string $testMarker Comment which precedes the test case.
	 * @param bool   $expected   Expected function output.
	 *
	 * @dataProvider dataIsReference
	 *
	 * @return void
	 */
	public function testIsReference( $testMarker, $expected ) {
		$bitwiseAnd = $this->getTargetToken( $testMarker, T_BITWISE_AND );
		$result     = self::$phpcsFile->isReference( $bitwiseAnd );
		$this->assertSame( $expected, $result );
	}//end testIsReference()


	/**
	 * Data provider for the IsReference test.
	 *
	 * @see testIsReference()
	 *
	 * @return array<string, array<string, string|bool>>
	 */
	public static function dataIsReference() {
		return array(
			'issue-1971-list-first-in-file'                => array(
				'testMarker' => '/* testTokenizerIssue1971PHPCSlt330gt271A */',
				'expected'   => true,
			),
			'issue-1971-list-first-in-file-nested'         => array(
				'testMarker' => '/* testTokenizerIssue1971PHPCSlt330gt271B */',
				'expected'   => true,
			),
			'bitwise and: param in function call'          => array(
				'testMarker' => '/* testBitwiseAndA */',
				'expected'   => false,
			),
			'bitwise and: in unkeyed short array, first value' => array(
				'testMarker' => '/* testBitwiseAndB */',
				'expected'   => false,
			),
			'bitwise and: in unkeyed short array, last value' => array(
				'testMarker' => '/* testBitwiseAndC */',
				'expected'   => false,
			),
			'bitwise and: in unkeyed long array, last value' => array(
				'testMarker' => '/* testBitwiseAndD */',
				'expected'   => false,
			),
			'bitwise and: in keyed short array, last value' => array(
				'testMarker' => '/* testBitwiseAndE */',
				'expected'   => false,
			),
			'bitwise and: in keyed long array, last value' => array(
				'testMarker' => '/* testBitwiseAndF */',
				'expected'   => false,
			),
			'bitwise and: in assignment'                   => array(
				'testMarker' => '/* testBitwiseAndG */',
				'expected'   => false,
			),
			'bitwise and: in param default value in function declaration' => array(
				'testMarker' => '/* testBitwiseAndH */',
				'expected'   => false,
			),
			'bitwise and: in param default value in closure declaration' => array(
				'testMarker' => '/* testBitwiseAndI */',
				'expected'   => false,
			),
			'reference: function declared to return by reference' => array(
				'testMarker' => '/* testFunctionReturnByReference */',
				'expected'   => true,
			),
			'reference: only param in function declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceA */',
				'expected'   => true,
			),
			'reference: last param in function declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceB */',
				'expected'   => true,
			),
			'reference: only param in closure declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceC */',
				'expected'   => true,
			),
			'reference: last param in closure declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceD */',
				'expected'   => true,
			),
			'reference: typed param in function declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceE */',
				'expected'   => true,
			),
			'reference: typed param in closure declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceF */',
				'expected'   => true,
			),
			'reference: variadic param in function declaration, pass by reference' => array(
				'testMarker' => '/* testFunctionPassByReferenceG */',
				'expected'   => true,
			),
			'reference: foreach value'                     => array(
				'testMarker' => '/* testForeachValueByReference */',
				'expected'   => true,
			),
			'reference: foreach key'                       => array(
				'testMarker' => '/* testForeachKeyByReference */',
				'expected'   => true,
			),
			'reference: keyed short array, first value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceA */',
				'expected'   => true,
			),
			'reference: keyed short array, last value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceB */',
				'expected'   => true,
			),
			'reference: unkeyed short array, only value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceC */',
				'expected'   => true,
			),
			'reference: unkeyed short array, last value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceD */',
				'expected'   => true,
			),
			'reference: keyed long array, first value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceE */',
				'expected'   => true,
			),
			'reference: keyed long array, last value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceF */',
				'expected'   => true,
			),
			'reference: unkeyed long array, only value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceG */',
				'expected'   => true,
			),
			'reference: unkeyed long array, last value, value by reference' => array(
				'testMarker' => '/* testArrayValueByReferenceH */',
				'expected'   => true,
			),
			'reference: variable, assign by reference'     => array(
				'testMarker' => '/* testAssignByReferenceA */',
				'expected'   => true,
			),
			'reference: variable, assign by reference, spacing variation' => array(
				'testMarker' => '/* testAssignByReferenceB */',
				'expected'   => true,
			),
			'reference: variable, assign by reference, concat assign' => array(
				'testMarker' => '/* testAssignByReferenceC */',
				'expected'   => true,
			),
			'reference: property, assign by reference'     => array(
				'testMarker' => '/* testAssignByReferenceD */',
				'expected'   => true,
			),
			'reference: function return value, assign by reference' => array(
				'testMarker' => '/* testAssignByReferenceE */',
				'expected'   => true,
			),
			'reference: function return value, assign by reference, null coalesce assign' => array(
				'testMarker' => '/* testAssignByReferenceF */',
				'expected'   => true,
			),
			'reference: unkeyed short list, first var, assign by reference' => array(
				'testMarker' => '/* testShortListAssignByReferenceNoKeyA */',
				'expected'   => true,
			),
			'reference: unkeyed short list, second var, assign by reference' => array(
				'testMarker' => '/* testShortListAssignByReferenceNoKeyB */',
				'expected'   => true,
			),
			'reference: unkeyed short list, nested var, assign by reference' => array(
				'testMarker' => '/* testNestedShortListAssignByReferenceNoKey */',
				'expected'   => true,
			),
			'reference: unkeyed long list, second var, assign by reference' => array(
				'testMarker' => '/* testLongListAssignByReferenceNoKeyA */',
				'expected'   => true,
			),
			'reference: unkeyed long list, first nested var, assign by reference' => array(
				'testMarker' => '/* testLongListAssignByReferenceNoKeyB */',
				'expected'   => true,
			),
			'reference: unkeyed long list, last nested var, assign by reference' => array(
				'testMarker' => '/* testLongListAssignByReferenceNoKeyC */',
				'expected'   => true,
			),
			'reference: keyed short list, first nested var, assign by reference' => array(
				'testMarker' => '/* testNestedShortListAssignByReferenceWithKeyA */',
				'expected'   => true,
			),
			'reference: keyed short list, last nested var, assign by reference' => array(
				'testMarker' => '/* testNestedShortListAssignByReferenceWithKeyB */',
				'expected'   => true,
			),
			'reference: keyed long list, only var, assign by reference' => array(
				'testMarker' => '/* testLongListAssignByReferenceWithKeyA */',
				'expected'   => true,
			),
			'reference: first param in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceA */',
				'expected'   => true,
			),
			'reference: last param in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceB */',
				'expected'   => true,
			),
			'reference: property in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceC */',
				'expected'   => true,
			),
			'reference: hierarchical self property in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceD */',
				'expected'   => true,
			),
			'reference: hierarchical parent property in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceE */',
				'expected'   => true,
			),
			'reference: hierarchical static property in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceF */',
				'expected'   => true,
			),
			'reference: static property in function call, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceG */',
				'expected'   => true,
			),
			'reference: static property in function call, first with FQN, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceH */',
				'expected'   => true,
			),
			'reference: static property in function call, last with FQN, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceI */',
				'expected'   => true,
			),
			'reference: static property in function call, last with namespace relative name, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceJ */',
				'expected'   => true,
			),
			'reference: static property in function call, last with PQN, pass by reference' => array(
				'testMarker' => '/* testPassByReferencePartiallyQualifiedName */',
				'expected'   => true,
			),
			'reference: new by reference'                  => array(
				'testMarker' => '/* testNewByReferenceA */',
				'expected'   => true,
			),
			'reference: new by reference as function call param' => array(
				'testMarker' => '/* testNewByReferenceB */',
				'expected'   => true,
			),
			'reference: closure use by reference'          => array(
				'testMarker' => '/* testUseByReference */',
				'expected'   => true,
			),
			'reference: closure use by reference, first param, with comment' => array(
				'testMarker' => '/* testUseByReferenceWithCommentFirstParam */',
				'expected'   => true,
			),
			'reference: closure use by reference, last param, with comment' => array(
				'testMarker' => '/* testUseByReferenceWithCommentSecondParam */',
				'expected'   => true,
			),
			'reference: arrow fn declared to return by reference' => array(
				'testMarker' => '/* testArrowFunctionReturnByReference */',
				'expected'   => true,
			),
			'bitwise and: first param default value in closure declaration' => array(
				'testMarker' => '/* testBitwiseAndExactParameterA */',
				'expected'   => false,
			),
			'reference: param in closure declaration, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceExactParameterB */',
				'expected'   => true,
			),
			'reference: variadic param in closure declaration, pass by reference' => array(
				'testMarker' => '/* testPassByReferenceExactParameterC */',
				'expected'   => true,
			),
			'bitwise and: last param default value in closure declaration' => array(
				'testMarker' => '/* testBitwiseAndExactParameterD */',
				'expected'   => false,
			),
			'reference: typed param in arrow fn declaration, pass by reference' => array(
				'testMarker' => '/* testArrowFunctionPassByReferenceA */',
				'expected'   => true,
			),
			'reference: variadic param in arrow fn declaration, pass by reference' => array(
				'testMarker' => '/* testArrowFunctionPassByReferenceB */',
				'expected'   => true,
			),
			'reference: closure declared to return by reference' => array(
				'testMarker' => '/* testClosureReturnByReference */',
				'expected'   => true,
			),
			'bitwise and: param default value in arrow fn declaration' => array(
				'testMarker' => '/* testBitwiseAndArrowFunctionInDefault */',
				'expected'   => false,
			),
			'reference: param pass by ref in arrow function' => array(
				'testMarker' => '/* testParamPassByReference */',
				'expected'   => true,
			),
			'issue-1284-short-list-directly-after-close-curly-control-structure' => array(
				'testMarker' => '/* testTokenizerIssue1284PHPCSlt280A */',
				'expected'   => true,
			),
			'issue-1284-short-list-directly-after-close-curly-control-structure-second-item' => array(
				'testMarker' => '/* testTokenizerIssue1284PHPCSlt280B */',
				'expected'   => true,
			),
			'issue-1284-short-array-directly-after-close-curly-control-structure' => array(
				'testMarker' => '/* testTokenizerIssue1284PHPCSlt280C */',
				'expected'   => true,
			),
		);
	}//end dataIsReference()
}//end class
