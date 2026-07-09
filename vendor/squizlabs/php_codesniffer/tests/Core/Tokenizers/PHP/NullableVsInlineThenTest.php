<?php
/**
 * Tests the retokenization of ? to T_NULLABLE or T_INLINE_THEN.
 *
 * @copyright 2025 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

/**
 * Tests the retokenization of ? to T_NULLABLE or T_INLINE_THEN.
 *
 * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
 */
final class NullableVsInlineThenTest extends AbstractTokenizerTestCase {



	/**
	 * Test that the ? at the start of type declarations is retokenized to T_NULLABLE.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataNullable
	 *
	 * @return void
	 */
	public function testNullable( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_NULLABLE, T_INLINE_THEN ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_NULLABLE, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_NULLABLE (code)' );
		$this->assertSame( 'T_NULLABLE', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_NULLABLE (type)' );
	}//end testNullable()


	/**
	 * Data provider.
	 *
	 * @see testNullable()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataNullable() {
		return array(
			'property declaration, readonly, no visibility' => array( '/* testNullableReadonlyOnly */' ),
			'property declaration, private set'          => array( '/* testNullablePrivateSet */' ),
			'property declaration, public and protected set' => array( '/* testNullablePublicProtectedSet */' ),
			'property declaration, final, no visibility' => array( '/* testNullableFinalOnly */' ),
			'property declaration, abstract, no visibility' => array( '/* testNullableAbstractOnly */' ),

			'closure param type, nullable int'           => array( '/* testClosureParamTypeNullableInt */' ),
			'closure param type, nullable callable'      => array( '/* testClosureParamTypeNullableCallable */' ),
			'closure param type, nullable string with comment, issue #1216' => array( '/* testClosureParamTypeNullableStringWithAttributeAndSlashComment */' ),
			'closure return type, nullable int'          => array( '/* testClosureReturnTypeNullableInt */' ),
			'function return type, nullable callable'    => array( '/* testFunctionReturnTypeNullableCallable */' ),
		);
	}//end dataNullable()


	/**
	 * Test that a "?" when used as part of a ternary expression is tokenized as `T_INLINE_THEN`.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataInlineThen
	 *
	 * @return void
	 */
	public function testInlineThen( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, array( T_NULLABLE, T_INLINE_THEN ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_INLINE_THEN, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_INLINE_THEN (code)' );
		$this->assertSame( 'T_INLINE_THEN', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_INLINE_THEN (type)' );
	}//end testInlineThen()


	/**
	 * Data provider.
	 *
	 * @see testInlineThen()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataInlineThen() {
		return array(
			'ternary in property default value'          => array( '/* testInlineThenInPropertyDefaultValue */' ),

			'ternary ? followed by array declaration'    => array( '/* testInlineThenWithArrayDeclaration */' ),

			'ternary ? followed by unqualified constant' => array( '/* testInlineThenWithUnqualifiedNameAndNothingElse */' ),
			'ternary ? followed by unqualified function call' => array( '/* testInlineThenWithUnqualifiedNameAndParens */' ),
			'ternary ? followed by unqualified static method call' => array( '/* testInlineThenWithUnqualifiedNameAndDoubleColon */' ),

			'ternary ? followed by fully qualified constant' => array( '/* testInlineThenWithFullyQualifiedNameAndNothingElse */' ),
			'ternary ? followed by fully qualified function call' => array( '/* testInlineThenWithFullyQualifiedNameAndParens */' ),
			'ternary ? followed by fully qualified static method call' => array( '/* testInlineThenWithFullyQualifiedNameAndDoubleColon */' ),

			'ternary ? followed by partially qualified constant' => array( '/* testInlineThenWithPartiallyQualifiedNameAndNothingElse */' ),
			'ternary ? followed by partially qualified function call' => array( '/* testInlineThenWithPartiallyQualifiedNameAndParens */' ),
			'ternary ? followed by partially qualified static method call' => array( '/* testInlineThenWithPartiallyQualifiedNameAndDoubleColon */' ),

			'ternary ? followed by namespace relative constant' => array( '/* testInlineThenWithNamespaceRelativeNameAndNothingElse */' ),
			'ternary ? followed by namespace relative function call' => array( '/* testInlineThenWithNamespaceRelativeNameAndParens */' ),
			'ternary ? followed by namespace relative static method call' => array( '/* testInlineThenWithNamespaceRelativeNameAndDoubleColon */' ),
		);
	}//end dataInlineThen()
}//end class
