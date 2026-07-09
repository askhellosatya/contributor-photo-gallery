<?php
/**
 * Tests that the array keyword is tokenized correctly.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2021 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\Tokenizer;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

final class CreateTokenMapArrayParenthesesTest extends AbstractTokenizerTestCase {



	/**
	 * Test that the array keyword is correctly tokenized as `T_ARRAY`.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent Optional. The token content to look for.
	 *
	 * @dataProvider dataArrayKeyword
	 * @covers       PHP_CodeSniffer\Tokenizers\Tokenizer::createTokenMap
	 *
	 * @return void
	 */
	public function testArrayKeyword( $testMarker, $testContent = 'array' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_ARRAY, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_ARRAY, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_ARRAY (code)' );

		$this->assertArrayHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is not set' );
		$this->assertArrayHasKey( 'parenthesis_opener', $tokenArray, 'Parenthesis opener is not set' );
		$this->assertArrayHasKey( 'parenthesis_closer', $tokenArray, 'Parenthesis closer is not set' );
	}//end testArrayKeyword()


	/**
	 * Data provider.
	 *
	 * @see testArrayKeyword()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataArrayKeyword() {
		return array(
			'empty array'                           => array(
				'testMarker' => '/* testEmptyArray */',
			),
			'array with space before parenthesis'   => array(
				'testMarker' => '/* testArrayWithSpace */',
			),
			'array with comment before parenthesis' => array(
				'testMarker'  => '/* testArrayWithComment */',
				'testContent' => 'Array',
			),
			'nested: outer array'                   => array(
				'testMarker' => '/* testNestingArray */',
			),
			'nested: inner array'                   => array(
				'testMarker' => '/* testNestedArray */',
			),
			'OO constant default value'             => array(
				'testMarker' => '/* testOOConstDefault */',
			),
		);
	}//end dataArrayKeyword()


	/**
	 * Test that the array keyword when used in a type declaration is correctly tokenized as `T_STRING`.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent Optional. The token content to look for.
	 *
	 * @dataProvider dataArrayType
	 * @covers       PHP_CodeSniffer\Tokenizers\Tokenizer::createTokenMap
	 *
	 * @return void
	 */
	public function testArrayType( $testMarker, $testContent = 'array' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_ARRAY, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );

		$this->assertArrayNotHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is set' );
		$this->assertArrayNotHasKey( 'parenthesis_opener', $tokenArray, 'Parenthesis opener is set' );
		$this->assertArrayNotHasKey( 'parenthesis_closer', $tokenArray, 'Parenthesis closer is set' );
	}//end testArrayType()


	/**
	 * Data provider.
	 *
	 * @see testArrayType()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataArrayType() {
		return array(
			'closure return type'        => array(
				'testMarker'  => '/* testClosureReturnType */',
				'testContent' => 'Array',
			),
			'function param type'        => array(
				'testMarker' => '/* testFunctionDeclarationParamType */',
			),
			'function union return type' => array(
				'testMarker' => '/* testFunctionDeclarationReturnType */',
			),
			'OO constant type'           => array(
				'testMarker' => '/* testOOConstType */',
			),
			'OO property type'           => array(
				'testMarker' => '/* testOOPropertyType */',
			),

			'OO constant DNF type'       => array(
				'testMarker' => '/* testOOConstDNFType */',
			),
			'OO property DNF type'       => array(
				'testMarker'  => '/* testOOPropertyDNFType */',
				'testContent' => 'ARRAY',
			),
			'function param DNF type'    => array(
				'testMarker' => '/* testFunctionDeclarationParamDNFType */',
			),
			'closure param DNF type'     => array(
				'testMarker' => '/* testClosureDeclarationParamDNFType */',
			),
			'arrow return DNF type'      => array(
				'testMarker'  => '/* testArrowDeclarationReturnDNFType */',
				'testContent' => 'Array',
			),
		);
	}//end dataArrayType()


	/**
	 * Verify that the retokenization of `T_ARRAY` tokens to `T_STRING` is handled correctly
	 * for tokens with the contents 'array' which aren't in actual fact the array keyword.
	 *
	 * @param string $testMarker  The comment prefacing the target token.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataNotArrayKeyword
	 * @covers       PHP_CodeSniffer\Tokenizers\Tokenizer::createTokenMap
	 *
	 * @return void
	 */
	public function testNotArrayKeyword( $testMarker, $testContent = 'array' ) {
		$tokens = $this->phpcsFile->getTokens();

		$token      = $this->getTargetToken( $testMarker, array( T_ARRAY, T_STRING ), $testContent );
		$tokenArray = $tokens[ $token ];

		// Make sure we're looking at the right token.
		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );

		$this->assertArrayNotHasKey( 'parenthesis_owner', $tokenArray, 'Parenthesis owner is set' );
		$this->assertArrayNotHasKey( 'parenthesis_opener', $tokenArray, 'Parenthesis opener is set' );
		$this->assertArrayNotHasKey( 'parenthesis_closer', $tokenArray, 'Parenthesis closer is set' );
	}//end testNotArrayKeyword()


	/**
	 * Data provider.
	 *
	 * @see testNotArrayKeyword()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataNotArrayKeyword() {
		return array(
			'class-constant-name'            => array(
				'testMarker'  => '/* testClassConst */',
				'testContent' => 'ARRAY',
			),
			'class-method-name'              => array(
				'testMarker' => '/* testClassMethod */',
			),
			'class-constant-name-after-type' => array(
				'testMarker'  => '/* testTypedOOConstName */',
				'testContent' => 'ARRAY',
			),
		);
	}//end dataNotArrayKeyword()
}//end class
