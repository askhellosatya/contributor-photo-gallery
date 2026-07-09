<?php
/**
 * Tests the tokenization of identifier names.
 *
 * As of PHP 8, identifier names are tokenized differently, depending on them being
 * either fully qualified, partially qualified or relative to the current namespace.
 *
 * This test file safeguards that in PHPCS 3.x this new form of tokenization is "undone"
 * and the tokenization of these identifier names is the same in all PHP versions
 * based on how these names were tokenized in PHP 5/7.
 *
 * {@link https://wiki.php.net/rfc/namespaced_names_as_token}
 * {@link https://github.com/squizlabs/PHP_CodeSniffer/issues/3041}
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class UndoNamespacedNameSingleTokenTest extends AbstractTokenizerTestCase {



	/**
	 * Test that identifier names are tokenized the same across PHP versions, based on the PHP 5/7 tokenization.
	 *
	 * @param string                       $testMarker     The comment prefacing the test.
	 * @param array<array<string, string>> $expectedTokens The tokenization expected.
	 *
	 * @dataProvider dataIdentifierTokenization
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testIdentifierTokenization( $testMarker, $expectedTokens ) {
		$tokens     = $this->phpcsFile->getTokens();
		$identifier = $this->getTargetToken( $testMarker, constant( $expectedTokens[0]['type'] ) );

		foreach ( $expectedTokens as $tokenInfo ) {
			$this->assertSame(
				constant( $tokenInfo['type'] ),
				$tokens[ $identifier ]['code'],
				'Token tokenized as ' . Tokens::tokenName( $tokens[ $identifier ]['code'] ) . ', not ' . $tokenInfo['type'] . ' (code)'
			);
			$this->assertSame(
				$tokenInfo['type'],
				$tokens[ $identifier ]['type'],
				'Token tokenized as ' . $tokens[ $identifier ]['type'] . ', not ' . $tokenInfo['type'] . ' (type)'
			);
			$this->assertSame( $tokenInfo['content'], $tokens[ $identifier ]['content'] );

			++$identifier;
		}
	}//end testIdentifierTokenization()


	/**
	 * Data provider.
	 *
	 * @see testIdentifierTokenization()
	 *
	 * @return array<string, array<string, string|array<array<string, string>>>>
	 */
	public static function dataIdentifierTokenization() {
		return array(
			'namespace declaration'                        => array(
				'testMarker'     => '/* testNamespaceDeclaration */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Package',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'namespace declaration, multi-level'           => array(
				'testMarker'     => '/* testNamespaceDeclarationWithLevels */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'SubLevel',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Domain',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, class'                  => array(
				'testMarker'     => '/* testUseStatement */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, class, multi-level'     => array(
				'testMarker'     => '/* testUseStatementWithLevels */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Domain',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, function'               => array(
				'testMarker'     => '/* testFunctionUseStatement */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'function',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, function, multi-level'  => array(
				'testMarker'     => '/* testFunctionUseStatementWithLevels */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'function',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_in_ns',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, constant'               => array(
				'testMarker'     => '/* testConstantUseStatement */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'const',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'CONSTANT_NAME',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, constant, multi-level'  => array(
				'testMarker'     => '/* testConstantUseStatementWithLevels */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'const',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'OTHER_CONSTANT',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'import use statement, multi-statement, unqualified class' => array(
				'testMarker'     => '/* testMultiUseUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'UnqualifiedClassName',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
				),
			),
			'import use statement, multi-statement, partially qualified class' => array(
				'testMarker'     => '/* testMultiUsePartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Sublevel',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'PartiallyClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'group use statement, multi-level prefix, mix inside group' => array(
				'testMarker'     => '/* testGroupUseStatement */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_OPEN_USE_GROUP',
						'content' => '{',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'AnotherDomain',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_grouped',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'const',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'CONSTANT_GROUPED',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Sub',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'YetAnotherDomain',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'SubLevelA',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_grouped_too',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'const',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'SubLevelB',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'CONSTANT_GROUPED_TOO',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_CLOSE_USE_GROUP',
						'content' => '}',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'class declaration'                            => array(
				'testMarker'     => '/* testClassName */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'MyClass',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'class declaration, extends fully qualified name' => array(
				'testMarker'     => '/* testExtendedFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'FQN',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'class declaration, implements namespace relative name' => array(
				'testMarker'     => '/* testImplementsRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
				),
			),
			'class declaration, implements fully qualified name' => array(
				'testMarker'     => '/* testImplementsFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Fully',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Qualified',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
				),
			),
			'class declaration, implements unqualified name' => array(
				'testMarker'     => '/* testImplementsUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Unqualified',
					),
					array(
						'type'    => 'T_COMMA',
						'content' => ',',
					),
				),
			),
			'class declaration, implements partially qualified name' => array(
				'testMarker'     => '/* testImplementsPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Sub',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'method declaration'                           => array(
				'testMarker'     => '/* testFunctionName */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'param type declaration, namespace relative name' => array(
				'testMarker'     => '/* testTypeDeclarationRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_TYPE_UNION',
						'content' => '|',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'object',
					),
				),
			),
			'param type declaration, fully qualified name' => array(
				'testMarker'     => '/* testTypeDeclarationFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Fully',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Qualified',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'param type declaration, unqualified name'     => array(
				'testMarker'     => '/* testTypeDeclarationUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Unqualified',
					),
					array(
						'type'    => 'T_TYPE_UNION',
						'content' => '|',
					),
					array(
						'type'    => 'T_FALSE',
						'content' => 'false',
					),
				),
			),
			'param type declaration, partially qualified name' => array(
				'testMarker'     => '/* testTypeDeclarationPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NULLABLE',
						'content' => '?',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Sublevel',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'return type declaration, fully qualified name' => array(
				'testMarker'     => '/* testReturnTypeFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NULLABLE',
						'content' => '?',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'function call, namespace relative name'       => array(
				'testMarker'     => '/* testFunctionCallRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'NameSpace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'function call, fully qualified name'          => array(
				'testMarker'     => '/* testFunctionCallFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Package',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'function call, unqualified name'              => array(
				'testMarker'     => '/* testFunctionCallUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'function call, partially qualified name'      => array(
				'testMarker'     => '/* testFunctionCallPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'catch, namespace relative name'               => array(
				'testMarker'     => '/* testCatchRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'SubLevel',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Exception',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'catch, fully qualified name'                  => array(
				'testMarker'     => '/* testCatchFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Exception',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'catch, unqualified name'                      => array(
				'testMarker'     => '/* testCatchUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Exception',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'catch, partially qualified name'              => array(
				'testMarker'     => '/* testCatchPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Exception',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'class instantiation, namespace relative name' => array(
				'testMarker'     => '/* testNewRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'class instantiation, fully qualified name'    => array(
				'testMarker'     => '/* testNewFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Vendor',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'class instantiation, unqualified name'        => array(
				'testMarker'     => '/* testNewUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'class instantiation, partially qualified name' => array(
				'testMarker'     => '/* testNewPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'double colon class access, namespace relative name' => array(
				'testMarker'     => '/* testDoubleColonRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_DOUBLE_COLON',
						'content' => '::',
					),
				),
			),
			'double colon class access, fully qualified name' => array(
				'testMarker'     => '/* testDoubleColonFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_DOUBLE_COLON',
						'content' => '::',
					),
				),
			),
			'double colon class access, unqualified name'  => array(
				'testMarker'     => '/* testDoubleColonUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_DOUBLE_COLON',
						'content' => '::',
					),
				),
			),
			'double colon class access, partially qualified name' => array(
				'testMarker'     => '/* testDoubleColonPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Level',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_DOUBLE_COLON',
						'content' => '::',
					),
				),
			),
			'instanceof, namespace relative name'          => array(
				'testMarker'     => '/* testInstanceOfRelative */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'instanceof, fully qualified name'             => array(
				'testMarker'     => '/* testInstanceOfFQN */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Full',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_CLOSE_PARENTHESIS',
						'content' => ')',
					),
				),
			),
			'instanceof, unqualified name'                 => array(
				'testMarker'     => '/* testInstanceOfUnqualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
				),
			),
			'instanceof, partially qualified name'         => array(
				'testMarker'     => '/* testInstanceOfPartiallyQualified */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Partially',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'ClassName',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'exit in partially qualified name (start)'     => array(
				'testMarker'     => '/* testExitInNamespacedNameStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Exit',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'exit in fully qualified name (middle)'        => array(
				'testMarker'     => '/* testExitInNamespacedNameMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'exit',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'exit in fully qualified name (end)'           => array(
				'testMarker'     => '/* testExitInNamespacedNameEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Exit',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'PHP 8.4 exit as function call, fully qualified' => array(
				'testMarker'     => '/* testFullyQualifiedExitFunctionCall */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_EXIT',
						'content' => 'Exit',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'exit as constant, fully qualified (illegal)'  => array(
				'testMarker'     => '/* testFullyQualifiedExitConstant */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_EXIT',
						'content' => 'exit',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'die in partially qualified name (start)'      => array(
				'testMarker'     => '/* testDieInNamespacedNameStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Die',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'die in fully qualified name (middle)'         => array(
				'testMarker'     => '/* testDieInNamespacedNameMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'die',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'die in fully qualified name (end)'            => array(
				'testMarker'     => '/* testDieInNamespacedNameEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'DIE',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'PHP 8.4 die as function call, fully qualified' => array(
				'testMarker'     => '/* testFullyQualifiedDieFunctionCall */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_EXIT',
						'content' => 'die',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'die as constant, fully qualified (illegal)'   => array(
				'testMarker'     => '/* testFullyQualifiedDieConstant */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_EXIT',
						'content' => 'DIE',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'false in partially qualified name (start)'    => array(
				'testMarker'     => '/* testFalseInNamespacedNameStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'False',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'false in fully qualified name (middle)'       => array(
				'testMarker'     => '/* testFalseInNamespacedNameMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'false',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'false in fully qualified name (end)'          => array(
				'testMarker'     => '/* testFalseInNamespacedNameEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'FALSE',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'false, fully qualified'                       => array(
				'testMarker'     => '/* testFullyQualifiedFalse */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_FALSE',
						'content' => 'false',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'true in partially qualified name (start)'     => array(
				'testMarker'     => '/* testTrueInNamespacedNameStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'True',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'true in fully qualified name (middle)'        => array(
				'testMarker'     => '/* testTrueInNamespacedNameMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'true',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'true in fully qualified name (end)'           => array(
				'testMarker'     => '/* testTrueInNamespacedNameEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'True',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'true, fully qualified'                        => array(
				'testMarker'     => '/* testFullyQualifiedTrue */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_TRUE',
						'content' => 'TRUE',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'null in partially qualified name (start)'     => array(
				'testMarker'     => '/* testNullInNamespacedNameStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_STRING',
						'content' => 'Null',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'null in fully qualified name (middle)'        => array(
				'testMarker'     => '/* testNullInNamespacedNameMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Null',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'null in fully qualified name (end)'           => array(
				'testMarker'     => '/* testNullInNamespacedNameEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Not',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'null',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),
			'null, fully qualified'                        => array(
				'testMarker'     => '/* testFullyQualifiedNull */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_NULL',
						'content' => 'Null',
					),
					array(
						'type'    => 'T_SEMICOLON',
						'content' => ';',
					),
				),
			),

			'function call, namespace relative, with whitespace (invalid in PHP 8)' => array(
				'testMarker'     => '/* testInvalidInPHP8Whitespace */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NAMESPACE',
						'content' => 'namespace',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Sublevel',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '          ',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'function_name',
					),
					array(
						'type'    => 'T_OPEN_PARENTHESIS',
						'content' => '(',
					),
				),
			),
			'double colon class access, fully qualified, with whitespace and comments (invalid in PHP 8)' => array(
				'testMarker'     => '/* testInvalidInPHP8Comments */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Fully',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '// phpcs:ignore Stnd.Cat.Sniff -- for reasons
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Qualified',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* comment */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_NS_SEPARATOR',
						'content' => '\\',
					),
					array(
						'type'    => 'T_STRING',
						'content' => 'Name',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
		);
	}//end dataIdentifierTokenization()
}//end class
