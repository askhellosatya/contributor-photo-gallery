<?php
/**
 * Tests that typed OO constants will be tokenized correctly for:
 * - the type keywords, including keywords like array (T_STRING).
 * - the ? in nullable types
 * - namespaced name types (PHPCS 3.x vs 4.x).
 * - the | in union types
 * - the & in intersection types
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class TypedConstantsTest extends AbstractTokenizerTestCase {



	/**
	 * Test that a ? after a "const" which is not the constant keyword is tokenized as ternary then, not as the nullable operator.
	 *
	 * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testTernaryIsInlineThen() {
		$tokens = $this->phpcsFile->getTokens();
		$target = $this->getTargetToken( '/* testTernaryIsTernaryAfterConst */', array( T_NULLABLE, T_INLINE_THEN ) );

		$this->assertSame(
			T_INLINE_THEN,
			$tokens[ $target ]['code'],
			'Token tokenized as ' . Tokens::tokenName( $tokens[ $target ]['code'] ) . ', not T_INLINE_THEN (code)'
		);
		$this->assertSame(
			'T_INLINE_THEN',
			$tokens[ $target ]['type'],
			'Token tokenized as ' . $tokens[ $target ]['type'] . ', not T_INLINE_THEN (type)'
		);
	}//end testTernaryIsInlineThen()


	/**
	 * Test the token name for an untyped constant is tokenized as T_STRING.
	 *
	 * @param string $testMarker The comment prefacing the target token.
	 *
	 * @dataProvider dataUntypedConstant
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testUntypedConstant( $testMarker ) {
		$tokens = $this->phpcsFile->getTokens();
		$target = $this->getTargetToken( $testMarker, T_CONST );

		for ( $i = ( $target + 1 ); $tokens[ $i ]['code'] !== T_EQUAL; $i++ ) {
			if ( isset( Tokens::$emptyTokens[ $tokens[ $i ]['code'] ] ) === true ) {
				// Ignore whitespace and comments, not interested in the tokenization of those.
				continue;
			}

			$this->assertSame(
				T_STRING,
				$tokens[ $i ]['code'],
				'Token tokenized as ' . Tokens::tokenName( $tokens[ $i ]['code'] ) . ', not T_STRING (code)'
			);
			$this->assertSame(
				'T_STRING',
				$tokens[ $i ]['type'],
				'Token tokenized as ' . $tokens[ $i ]['type'] . ', not T_STRING (type)'
			);
		}
	}//end testUntypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testUntypedConstant()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataUntypedConstant() {
		return array(
			'non OO constant (untyped)'                  => array(
				'testMarker' => '/* testGlobalConstantCannotBeTyped */',
			),
			'OO constant, final, untyped'                => array(
				'testMarker' => '/* testClassConstFinalUntyped */',
			),
			'OO constant, public, untyped, with comment' => array(
				'testMarker' => '/* testClassConstVisibilityUntyped */',
			),
		);
	}//end dataUntypedConstant()


	/**
	 * Test the tokens in the type of a typed constant as well as the constant name are tokenized correctly.
	 *
	 * @param string            $testMarker The comment prefacing the target token.
	 * @param array<int|string> $sequence   The expected token sequence.
	 *
	 * @dataProvider dataTypedConstant
	 * @dataProvider dataNullableTypedConstant
	 * @dataProvider dataUnionTypedConstant
	 * @dataProvider dataIntersectionTypedConstant
	 * @dataProvider dataDNFTypedConstant
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::processAdditional
	 *
	 * @return void
	 */
	public function testTypedConstant( $testMarker, array $sequence ) {
		$tokens = $this->phpcsFile->getTokens();
		$target = $this->getTargetToken( $testMarker, T_CONST );

		$current = 0;
		for ( $i = ( $target + 1 ); $tokens[ $i ]['code'] !== T_EQUAL; $i++ ) {
			if ( isset( Tokens::$emptyTokens[ $tokens[ $i ]['code'] ] ) === true ) {
				// Ignore whitespace and comments, not interested in the tokenization of those.
				continue;
			}

			$this->assertSame(
				$sequence[ $current ],
				$tokens[ $i ]['code'],
				'Token tokenized as ' . Tokens::tokenName( $tokens[ $i ]['code'] ) . ', not ' . Tokens::tokenName( $sequence[ $current ] ) . ' (code)'
			);

			++$current;
		}
	}//end testTypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testTypedConstant()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataTypedConstant() {
		$data = array(
			'simple type: true'                        => array(
				'testMarker' => '/* testClassConstTypedTrue */',
				'sequence'   => array( T_TRUE ),
			),
			'simple type: false'                       => array(
				'testMarker' => '/* testClassConstTypedFalse */',
				'sequence'   => array( T_FALSE ),
			),
			'simple type: null'                        => array(
				'testMarker' => '/* testClassConstTypedNull */',
				'sequence'   => array( T_NULL ),
			),
			'simple type: bool'                        => array(
				'testMarker' => '/* testClassConstTypedBool */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: int'                         => array(
				'testMarker' => '/* testClassConstTypedInt */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: float'                       => array(
				'testMarker' => '/* testClassConstTypedFloat */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: string'                      => array(
				'testMarker' => '/* testClassConstTypedString */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: array'                       => array(
				'testMarker' => '/* testClassConstTypedArray */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: object'                      => array(
				'testMarker' => '/* testClassConstTypedObject */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: iterable'                    => array(
				'testMarker' => '/* testClassConstTypedIterable */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: mixed'                       => array(
				'testMarker' => '/* testClassConstTypedMixed */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: unqualified name'            => array(
				'testMarker' => '/* testClassConstTypedClassUnqualified */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: fully qualified name'        => array(
				'testMarker' => '/* testClassConstTypedClassFullyQualified */',
				'sequence'   => array(
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'simple type: namespace relative name'     => array(
				'testMarker' => '/* testClassConstTypedClassNamespaceRelative */',
				'sequence'   => array(
					T_NAMESPACE,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'simple type: partially qualified name'    => array(
				'testMarker' => '/* testClassConstTypedClassPartiallyQualified */',
				'sequence'   => array(
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'simple type: parent'                      => array(
				'testMarker' => '/* testClassConstTypedParent */',
				'sequence'   => array( T_PARENT ),
			),

			'simple type: callable (invalid)'          => array(
				'testMarker' => '/* testClassConstTypedCallable */',
				'sequence'   => array( T_CALLABLE ),
			),
			'simple type: void (invalid)'              => array(
				'testMarker' => '/* testClassConstTypedVoid */',
				'sequence'   => array( T_STRING ),
			),
			'simple type: NEVER (invalid)'             => array(
				'testMarker' => '/* testClassConstTypedNever */',
				'sequence'   => array( T_STRING ),
			),

			'simple type: self (only valid in enum)'   => array(
				'testMarker' => '/* testEnumConstTypedSelf */',
				'sequence'   => array( T_SELF ),
			),
			'simple type: static (only valid in enum)' => array(
				'testMarker' => '/* testEnumConstTypedStatic */',
				'sequence'   => array( T_STATIC ),
			),
		);

		// The constant name, as the last token in the sequence, is always T_STRING.
		foreach ( $data as $key => $value ) {
			$data[ $key ]['sequence'][] = T_STRING;
		}

		return $data;
	}//end dataTypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testTypedConstant()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataNullableTypedConstant() {
		$data = array(
			// Global constants cannot be typed in PHP, but that's not our concern.
			'global typed constant, invalid, ?int'       => array(
				'testMarker' => '/* testGlobalConstantTypedShouldStillBeHandled */',
				'sequence'   => array( T_STRING ),
			),

			// OO constants.
			'nullable type: true'                        => array(
				'testMarker' => '/* testTraitConstTypedNullableTrue */',
				'sequence'   => array( T_TRUE ),
			),
			'nullable type: false'                       => array(
				'testMarker' => '/* testTraitConstTypedNullableFalse */',
				'sequence'   => array( T_FALSE ),
			),
			'nullable type: null'                        => array(
				'testMarker' => '/* testTraitConstTypedNullableNull */',
				'sequence'   => array( T_NULL ),
			),
			'nullable type: bool'                        => array(
				'testMarker' => '/* testTraitConstTypedNullableBool */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: int'                         => array(
				'testMarker' => '/* testTraitConstTypedNullableInt */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: float'                       => array(
				'testMarker' => '/* testTraitConstTypedNullableFloat */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: string'                      => array(
				'testMarker' => '/* testTraitConstTypedNullableString */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: array'                       => array(
				'testMarker' => '/* testTraitConstTypedNullableArray */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: object'                      => array(
				'testMarker' => '/* testTraitConstTypedNullableObject */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: iterable'                    => array(
				'testMarker' => '/* testTraitConstTypedNullableIterable */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: mixed'                       => array(
				'testMarker' => '/* testTraitConstTypedNullableMixed */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: unqualified name'            => array(
				'testMarker' => '/* testTraitConstTypedNullableClassUnqualified */',
				'sequence'   => array( T_STRING ),
			),
			'nullable type: fully qualified name'        => array(
				'testMarker' => '/* testTraitConstTypedNullableClassFullyQualified */',
				'sequence'   => array(
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'nullable type: namespace relative name'     => array(
				'testMarker' => '/* testTraitConstTypedNullableClassNamespaceRelative */',
				'sequence'   => array(
					T_NAMESPACE,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'nullable type: partially qualified name'    => array(
				'testMarker' => '/* testTraitConstTypedNullableClassPartiallyQualified */',
				'sequence'   => array(
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'nullable type: parent'                      => array(
				'testMarker' => '/* testTraitConstTypedNullableParent */',
				'sequence'   => array( T_PARENT ),
			),

			'nullable type: self (only valid in enum)'   => array(
				'testMarker' => '/* testEnumConstTypedNullableSelf */',
				'sequence'   => array( T_SELF ),
			),
			'nullable type: static (only valid in enum)' => array(
				'testMarker' => '/* testEnumConstTypedNullableStatic */',
				'sequence'   => array( T_STATIC ),
			),
		);

		// The nullable operator, as the first token in the sequence, is always T_NULLABLE.
		// The constant name, as the last token in the sequence, is always T_STRING.
		foreach ( $data as $key => $value ) {
			array_unshift( $data[ $key ]['sequence'], T_NULLABLE );
			$data[ $key ]['sequence'][] = T_STRING;
		}

		return $data;
	}//end dataNullableTypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testTypedConstant()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataUnionTypedConstant() {
		$data = array(
			'union type: true|null'                      => array(
				'testMarker' => '/* testInterfaceConstTypedUnionTrueNull */',
				'sequence'   => array(
					T_TRUE,
					T_TYPE_UNION,
					T_NULL,
				),
			),
			'union type: array|object'                   => array(
				'testMarker' => '/* testInterfaceConstTypedUnionArrayObject */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
				),
			),
			'union type: string|array|int'               => array(
				'testMarker' => '/* testInterfaceConstTypedUnionStringArrayInt */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
				),
			),
			'union type: float|bool|array'               => array(
				'testMarker' => '/* testInterfaceConstTypedUnionFloatBoolArray */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
				),
			),
			'union type: iterable|false'                 => array(
				'testMarker' => '/* testInterfaceConstTypedUnionIterableFalse */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_FALSE,
				),
			),
			'union type: Unqualified|Namespace\Relative' => array(
				'testMarker' => '/* testInterfaceConstTypedUnionUnqualifiedNamespaceRelative */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_NAMESPACE,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'union type: FQN|Partial'                    => array(
				'testMarker' => '/* testInterfaceConstTypedUnionFullyQualifiedPartiallyQualified */',
				'sequence'   => array(
					T_NS_SEPARATOR,
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
					T_TYPE_UNION,
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
		);

		// The constant name, as the last token in the sequence, is always T_STRING.
		foreach ( $data as $key => $value ) {
			$data[ $key ]['sequence'][] = T_STRING;
		}

		return $data;
	}//end dataUnionTypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testTypedConstant()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataIntersectionTypedConstant() {
		$data = array(
			'intersection type: Unqualified&Namespace\Relative' => array(
				'testMarker' => '/* testEnumConstTypedIntersectUnqualifiedNamespaceRelative */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_INTERSECTION,
					T_NAMESPACE,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'intersection type: FQN&Partial' => array(
				'testMarker' => '/* testEnumConstTypedIntersectFullyQualifiedPartiallyQualified */',
				'sequence'   => array(
					T_NS_SEPARATOR,
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
		);

		// The constant name, as the last token in the sequence, is always T_STRING.
		foreach ( $data as $key => $value ) {
			$data[ $key ]['sequence'][] = T_STRING;
		}

		return $data;
	}//end dataIntersectionTypedConstant()


	/**
	 * Data provider.
	 *
	 * @see testTypedConstant()
	 *
	 * @return array<string, array<string, string|array<int|string>>>
	 */
	public static function dataDNFTypedConstant() {
		$data = array(
			'DNF type: null after'                 => array(
				'testMarker' => '/* testAnonClassConstDNFTypeNullAfter */',
				'sequence'   => array(
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_NULL,
				),
			),
			'DNF type: null before'                => array(
				'testMarker' => '/* testAnonClassConstDNFTypeNullBefore */',
				'sequence'   => array(
					T_NULL,
					T_TYPE_UNION,
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
				),
			),
			'DNF type: false before'               => array(
				'testMarker' => '/* testAnonClassConstDNFTypeFalseBefore */',
				'sequence'   => array(
					T_FALSE,
					T_TYPE_UNION,
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
				),
			),
			'DNF type: true after'                 => array(
				'testMarker' => '/* testAnonClassConstDNFTypeTrueAfter */',
				'sequence'   => array(
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_TRUE,
				),
			),
			'DNF type: true before, false after'   => array(
				'testMarker' => '/* testAnonClassConstDNFTypeTrueBeforeFalseAfter */',
				'sequence'   => array(
					T_TRUE,
					T_TYPE_UNION,
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_FALSE,
				),
			),
			'DNF type: array after'                => array(
				'testMarker' => '/* testAnonClassConstDNFTypeArrayAfter */',
				'sequence'   => array(
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_STRING,
				),
			),
			'DNF type: array before'               => array(
				'testMarker' => '/* testAnonClassConstDNFTypeArrayBefore */',
				'sequence'   => array(
					T_STRING,
					T_TYPE_UNION,
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
				),
			),
			'DNF type: invalid nullable DNF'       => array(
				'testMarker' => '/* testAnonClassConstDNFTypeInvalidNullable */',
				'sequence'   => array(
					T_NULLABLE,
					T_TYPE_OPEN_PARENTHESIS,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_STRING,
				),
			),
			'DNF type: FQN/namespace relative/partially qualified names' => array(
				'testMarker' => '/* testAnonClassConstDNFTypeFQNRelativePartiallyQualified */',
				'sequence'   => array(
					T_TYPE_OPEN_PARENTHESIS,
					T_NS_SEPARATOR,
					T_STRING,
					T_TYPE_INTERSECTION,
					T_NAMESPACE,
					T_NS_SEPARATOR,
					T_STRING,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_STRING,
					T_NS_SEPARATOR,
					T_STRING,
				),
			),
			'DNF type: invalid self/parent/static' => array(
				'testMarker' => '/* testAnonClassConstDNFTypeParentSelfStatic */',
				'sequence'   => array(
					T_TYPE_OPEN_PARENTHESIS,
					T_PARENT,
					T_TYPE_INTERSECTION,
					T_SELF,
					T_TYPE_CLOSE_PARENTHESIS,
					T_TYPE_UNION,
					T_STATIC,
				),
			),
		);

		// The constant name, as the last token in the sequence, is always T_STRING.
		foreach ( $data as $key => $value ) {
			$data[ $key ]['sequence'][] = T_STRING;
		}

		return $data;
	}//end dataDNFTypedConstant()
}//end class
