<?php
/**
 * Tests the conversion of PHP native context sensitive keywords to T_STRING.
 *
 * @author    Jaroslav Hanslík <kukulich@kukulich.cz>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class ContextSensitiveKeywordsTest extends AbstractTokenizerTestCase {



	/**
	 * Test that context sensitive keyword is tokenized as string when it should be string.
	 *
	 * @param string $testMarker The comment which prefaces the target token in the test file.
	 *
	 * @dataProvider dataStrings
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testStrings( $testMarker ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, ( Tokens::$contextSensitiveKeywords + array( T_STRING ) ) );
		$tokenArray = $tokens[ $target ];

		$this->assertSame( T_STRING, $tokenArray['code'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (code)' );
		$this->assertSame( 'T_STRING', $tokenArray['type'], 'Token tokenized as ' . $tokenArray['type'] . ', not T_STRING (type)' );
	}//end testStrings()


	/**
	 * Data provider.
	 *
	 * @see testStrings()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataStrings() {
		return array(
			'constant declaration: abstract'               => array( '/* testAbstract */' ),
			'constant declaration: array'                  => array( '/* testArray */' ),
			'constant declaration: as'                     => array( '/* testAs */' ),
			'constant declaration: break'                  => array( '/* testBreak */' ),
			'constant declaration: callable'               => array( '/* testCallable */' ),
			'constant declaration: case'                   => array( '/* testCase */' ),
			'constant declaration: catch'                  => array( '/* testCatch */' ),
			'constant declaration: class'                  => array( '/* testClass */' ),
			'constant declaration: clone'                  => array( '/* testClone */' ),
			'constant declaration: const'                  => array( '/* testConst */' ),
			'constant declaration: continue'               => array( '/* testContinue */' ),
			'constant declaration: declare'                => array( '/* testDeclare */' ),
			'constant declaration: default'                => array( '/* testDefault */' ),
			'constant declaration: do'                     => array( '/* testDo */' ),
			'constant declaration: echo'                   => array( '/* testEcho */' ),
			'constant declaration: else'                   => array( '/* testElse */' ),
			'constant declaration: elseif'                 => array( '/* testElseIf */' ),
			'constant declaration: empty'                  => array( '/* testEmpty */' ),
			'constant declaration: enddeclare'             => array( '/* testEndDeclare */' ),
			'constant declaration: endfor'                 => array( '/* testEndFor */' ),
			'constant declaration: endforeach'             => array( '/* testEndForeach */' ),
			'constant declaration: endif'                  => array( '/* testEndIf */' ),
			'constant declaration: endswitch'              => array( '/* testEndSwitch */' ),
			'constant declaration: endwhile'               => array( '/* testEndWhile */' ),
			'constant declaration: enum'                   => array( '/* testEnum */' ),
			'constant declaration: eval'                   => array( '/* testEval */' ),
			'constant declaration: exit'                   => array( '/* testExit */' ),
			'constant declaration: extends'                => array( '/* testExtends */' ),
			'constant declaration: final'                  => array( '/* testFinal */' ),
			'constant declaration: finally'                => array( '/* testFinally */' ),
			'constant declaration: fn'                     => array( '/* testFn */' ),
			'constant declaration: for'                    => array( '/* testFor */' ),
			'constant declaration: foreach'                => array( '/* testForeach */' ),
			'constant declaration: function'               => array( '/* testFunction */' ),
			'constant declaration: global'                 => array( '/* testGlobal */' ),
			'constant declaration: goto'                   => array( '/* testGoto */' ),
			'constant declaration: if'                     => array( '/* testIf */' ),
			'constant declaration: implements'             => array( '/* testImplements */' ),
			'constant declaration: include'                => array( '/* testInclude */' ),
			'constant declaration: include_once'           => array( '/* testIncludeOnce */' ),
			'constant declaration: instanceof'             => array( '/* testInstanceOf */' ),
			'constant declaration: insteadof'              => array( '/* testInsteadOf */' ),
			'constant declaration: interface'              => array( '/* testInterface */' ),
			'constant declaration: isset'                  => array( '/* testIsset */' ),
			'constant declaration: list'                   => array( '/* testList */' ),
			'constant declaration: match'                  => array( '/* testMatch */' ),
			'constant declaration: namespace'              => array( '/* testNamespace */' ),
			'constant declaration: new'                    => array( '/* testNew */' ),
			'constant declaration: print'                  => array( '/* testPrint */' ),
			'constant declaration: private'                => array( '/* testPrivate */' ),
			'constant declaration: protected'              => array( '/* testProtected */' ),
			'constant declaration: public'                 => array( '/* testPublic */' ),
			'constant declaration: readonly'               => array( '/* testReadonly */' ),
			'constant declaration: require'                => array( '/* testRequire */' ),
			'constant declaration: require_once'           => array( '/* testRequireOnce */' ),
			'constant declaration: return'                 => array( '/* testReturn */' ),
			'constant declaration: static'                 => array( '/* testStatic */' ),
			'constant declaration: switch'                 => array( '/* testSwitch */' ),
			'constant declaration: throws'                 => array( '/* testThrows */' ),
			'constant declaration: trait'                  => array( '/* testTrait */' ),
			'constant declaration: try'                    => array( '/* testTry */' ),
			'constant declaration: unset'                  => array( '/* testUnset */' ),
			'constant declaration: use'                    => array( '/* testUse */' ),
			'constant declaration: var'                    => array( '/* testVar */' ),
			'constant declaration: while'                  => array( '/* testWhile */' ),
			'constant declaration: yield'                  => array( '/* testYield */' ),
			'constant declaration: yield_from'             => array( '/* testYieldFrom */' ),
			'constant declaration: and'                    => array( '/* testAnd */' ),
			'constant declaration: or'                     => array( '/* testOr */' ),
			'constant declaration: xor'                    => array( '/* testXor */' ),

			'constant declaration: array in type'          => array( '/* testArrayIsTstringInConstType */' ),
			'constant declaration: array, name after type' => array( '/* testArrayNameForTypedConstant */' ),
			'constant declaration: static, name after type' => array( '/* testStaticIsNameForTypedConstant */' ),
			'constant declaration: private, name after type' => array( '/* testPrivateNameForUnionTypedConstant */' ),
			'constant declaration: final, name after type' => array( '/* testFinalNameForIntersectionTypedConstant */' ),

			'namespace declaration: class'                 => array( '/* testKeywordAfterNamespaceShouldBeString */' ),
			'namespace declaration (partial): my'          => array( '/* testNamespaceNameIsString1 */' ),
			'namespace declaration (partial): class'       => array( '/* testNamespaceNameIsString2 */' ),
			'namespace declaration (partial): foreach'     => array( '/* testNamespaceNameIsString3 */' ),

			'function declaration: eval'                   => array( '/* testKeywordAfterFunctionShouldBeString */' ),
			'function declaration with return by ref: switch' => array( '/* testKeywordAfterFunctionByRefShouldBeString */' ),
			'function declaration with return by ref: static' => array( '/* testKeywordStaticAfterFunctionByRefShouldBeString */' ),

			'function call: static'                        => array( '/* testKeywordAsFunctionCallNameShouldBeStringStatic */' ),
			'method call: static'                          => array( '/* testKeywordAsMethodCallNameShouldBeStringStatic */' ),
			'method call: static with dnf look a like param' => array( '/* testKeywordAsFunctionCallNameShouldBeStringStaticDNFLookaLike */' ),
		);
	}//end dataStrings()


	/**
	 * Test that context sensitive keyword is tokenized as keyword when it should be keyword.
	 *
	 * @param string $testMarker        The comment which prefaces the target token in the test file.
	 * @param string $expectedTokenType The expected token type.
	 *
	 * @dataProvider dataKeywords
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testKeywords( $testMarker, $expectedTokenType ) {
		$tokenTargets   = Tokens::$contextSensitiveKeywords;
		$tokenTargets[] = T_STRING;
		$tokenTargets[] = T_ANON_CLASS;
		$tokenTargets[] = T_MATCH_DEFAULT;
		$tokenTargets[] = T_PRIVATE_SET;
		$tokenTargets[] = T_PROTECTED_SET;
		$tokenTargets[] = T_PUBLIC_SET;

		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken( $testMarker, $tokenTargets );
		$tokenArray = $tokens[ $target ];

		$this->assertSame(
			constant( $expectedTokenType ),
			$tokenArray['code'],
			'Token tokenized as ' . $tokenArray['type'] . ', not ' . $expectedTokenType . ' (code)'
		);
		$this->assertSame(
			$expectedTokenType,
			$tokenArray['type'],
			'Token tokenized as ' . $tokenArray['type'] . ', not ' . $expectedTokenType . ' (type)'
		);
	}//end testKeywords()


	/**
	 * Data provider.
	 *
	 * @see testKeywords()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataKeywords() {
		return array(
			'namespace: declaration'                 => array(
				'testMarker'        => '/* testNamespaceIsKeyword */',
				'expectedTokenType' => 'T_NAMESPACE',
			),
			'array: default value in const decl'     => array(
				'testMarker'        => '/* testArrayIsKeywordInConstDefault */',
				'expectedTokenType' => 'T_ARRAY',
			),
			'static: type in constant declaration'   => array(
				'testMarker'        => '/* testStaticIsKeywordAsConstType */',
				'expectedTokenType' => 'T_STATIC',
			),
			'static: value in constant declaration'  => array(
				'testMarker'        => '/* testStaticIsKeywordAsConstDefault */',
				'expectedTokenType' => 'T_STATIC',
			),

			'abstract: class declaration'            => array(
				'testMarker'        => '/* testAbstractIsKeyword */',
				'expectedTokenType' => 'T_ABSTRACT',
			),
			'class: declaration'                     => array(
				'testMarker'        => '/* testClassIsKeyword */',
				'expectedTokenType' => 'T_CLASS',
			),
			'extends: in class declaration'          => array(
				'testMarker'        => '/* testExtendsIsKeyword */',
				'expectedTokenType' => 'T_EXTENDS',
			),
			'implements: in class declaration'       => array(
				'testMarker'        => '/* testImplementsIsKeyword */',
				'expectedTokenType' => 'T_IMPLEMENTS',
			),
			'use: in trait import'                   => array(
				'testMarker'        => '/* testUseIsKeyword */',
				'expectedTokenType' => 'T_USE',
			),
			'insteadof: in trait import'             => array(
				'testMarker'        => '/* testInsteadOfIsKeyword */',
				'expectedTokenType' => 'T_INSTEADOF',
			),
			'as: in trait import'                    => array(
				'testMarker'        => '/* testAsIsKeyword */',
				'expectedTokenType' => 'T_AS',
			),
			'const: declaration'                     => array(
				'testMarker'        => '/* testConstIsKeyword */',
				'expectedTokenType' => 'T_CONST',
			),
			'private: property declaration'          => array(
				'testMarker'        => '/* testPrivateIsKeyword */',
				'expectedTokenType' => 'T_PRIVATE',
			),
			'protected: property declaration'        => array(
				'testMarker'        => '/* testProtectedIsKeyword */',
				'expectedTokenType' => 'T_PROTECTED',
			),
			'public: property declaration'           => array(
				'testMarker'        => '/* testPublicIsKeyword */',
				'expectedTokenType' => 'T_PUBLIC',
			),
			'private(set): property declaration'     => array(
				'testMarker'        => '/* testPrivateSetIsKeyword */',
				'expectedTokenType' => 'T_PRIVATE_SET',
			),
			'protected(set): property declaration'   => array(
				'testMarker'        => '/* testProtectedSetIsKeyword */',
				'expectedTokenType' => 'T_PROTECTED_SET',
			),
			'public(set): property declaration'      => array(
				'testMarker'        => '/* testPublicSetIsKeyword */',
				'expectedTokenType' => 'T_PUBLIC_SET',
			),
			'var: property declaration'              => array(
				'testMarker'        => '/* testVarIsKeyword */',
				'expectedTokenType' => 'T_VAR',
			),
			'static: property declaration'           => array(
				'testMarker'        => '/* testStaticIsKeyword */',
				'expectedTokenType' => 'T_STATIC',
			),
			'readonly: property declaration'         => array(
				'testMarker'        => '/* testReadonlyIsKeywordForProperty */',
				'expectedTokenType' => 'T_READONLY',
			),
			'final: function declaration'            => array(
				'testMarker'        => '/* testFinalIsKeyword */',
				'expectedTokenType' => 'T_FINAL',
			),
			'function: declaration'                  => array(
				'testMarker'        => '/* testFunctionIsKeyword */',
				'expectedTokenType' => 'T_FUNCTION',
			),
			'callable: param type declaration'       => array(
				'testMarker'        => '/* testCallableIsKeyword */',
				'expectedTokenType' => 'T_CALLABLE',
			),
			'readonly: anon class declaration'       => array(
				'testMarker'        => '/* testReadonlyIsKeywordForAnonClass */',
				'expectedTokenType' => 'T_READONLY',
			),
			'return: statement'                      => array(
				'testMarker'        => '/* testReturnIsKeyword */',
				'expectedTokenType' => 'T_RETURN',
			),

			'interface: declaration'                 => array(
				'testMarker'        => '/* testInterfaceIsKeyword */',
				'expectedTokenType' => 'T_INTERFACE',
			),
			'trait: declaration'                     => array(
				'testMarker'        => '/* testTraitIsKeyword */',
				'expectedTokenType' => 'T_TRAIT',
			),
			'enum: declaration'                      => array(
				'testMarker'        => '/* testEnumIsKeyword */',
				'expectedTokenType' => 'T_ENUM',
			),

			'new: named instantiation'               => array(
				'testMarker'        => '/* testNewIsKeyword */',
				'expectedTokenType' => 'T_NEW',
			),
			'instanceof: comparison'                 => array(
				'testMarker'        => '/* testInstanceOfIsKeyword */',
				'expectedTokenType' => 'T_INSTANCEOF',
			),
			'clone'                                  => array(
				'testMarker'        => '/* testCloneIsKeyword */',
				'expectedTokenType' => 'T_CLONE',
			),

			'if'                                     => array(
				'testMarker'        => '/* testIfIsKeyword */',
				'expectedTokenType' => 'T_IF',
			),
			'empty'                                  => array(
				'testMarker'        => '/* testEmptyIsKeyword */',
				'expectedTokenType' => 'T_EMPTY',
			),
			'elseif'                                 => array(
				'testMarker'        => '/* testElseIfIsKeyword */',
				'expectedTokenType' => 'T_ELSEIF',
			),
			'else'                                   => array(
				'testMarker'        => '/* testElseIsKeyword */',
				'expectedTokenType' => 'T_ELSE',
			),
			'endif'                                  => array(
				'testMarker'        => '/* testEndIfIsKeyword */',
				'expectedTokenType' => 'T_ENDIF',
			),

			'for'                                    => array(
				'testMarker'        => '/* testForIsKeyword */',
				'expectedTokenType' => 'T_FOR',
			),
			'endfor'                                 => array(
				'testMarker'        => '/* testEndForIsKeyword */',
				'expectedTokenType' => 'T_ENDFOR',
			),

			'foreach'                                => array(
				'testMarker'        => '/* testForeachIsKeyword */',
				'expectedTokenType' => 'T_FOREACH',
			),
			'endforeach'                             => array(
				'testMarker'        => '/* testEndForeachIsKeyword */',
				'expectedTokenType' => 'T_ENDFOREACH',
			),

			'switch'                                 => array(
				'testMarker'        => '/* testSwitchIsKeyword */',
				'expectedTokenType' => 'T_SWITCH',
			),
			'case: in switch'                        => array(
				'testMarker'        => '/* testCaseIsKeyword */',
				'expectedTokenType' => 'T_CASE',
			),
			'default: in switch'                     => array(
				'testMarker'        => '/* testDefaultIsKeyword */',
				'expectedTokenType' => 'T_DEFAULT',
			),
			'endswitch'                              => array(
				'testMarker'        => '/* testEndSwitchIsKeyword */',
				'expectedTokenType' => 'T_ENDSWITCH',
			),
			'break: in switch'                       => array(
				'testMarker'        => '/* testBreakIsKeyword */',
				'expectedTokenType' => 'T_BREAK',
			),
			'continue: in switch'                    => array(
				'testMarker'        => '/* testContinueIsKeyword */',
				'expectedTokenType' => 'T_CONTINUE',
			),

			'do'                                     => array(
				'testMarker'        => '/* testDoIsKeyword */',
				'expectedTokenType' => 'T_DO',
			),
			'while'                                  => array(
				'testMarker'        => '/* testWhileIsKeyword */',
				'expectedTokenType' => 'T_WHILE',
			),
			'endwhile'                               => array(
				'testMarker'        => '/* testEndWhileIsKeyword */',
				'expectedTokenType' => 'T_ENDWHILE',
			),

			'try'                                    => array(
				'testMarker'        => '/* testTryIsKeyword */',
				'expectedTokenType' => 'T_TRY',
			),
			'throw: statement'                       => array(
				'testMarker'        => '/* testThrowIsKeyword */',
				'expectedTokenType' => 'T_THROW',
			),
			'catch'                                  => array(
				'testMarker'        => '/* testCatchIsKeyword */',
				'expectedTokenType' => 'T_CATCH',
			),
			'finally'                                => array(
				'testMarker'        => '/* testFinallyIsKeyword */',
				'expectedTokenType' => 'T_FINALLY',
			),

			'global'                                 => array(
				'testMarker'        => '/* testGlobalIsKeyword */',
				'expectedTokenType' => 'T_GLOBAL',
			),
			'echo'                                   => array(
				'testMarker'        => '/* testEchoIsKeyword */',
				'expectedTokenType' => 'T_ECHO',
			),
			'print: statement'                       => array(
				'testMarker'        => '/* testPrintIsKeyword */',
				'expectedTokenType' => 'T_PRINT',
			),
			'die: statement'                         => array(
				'testMarker'        => '/* testDieIsKeyword */',
				'expectedTokenType' => 'T_EXIT',
			),
			'eval'                                   => array(
				'testMarker'        => '/* testEvalIsKeyword */',
				'expectedTokenType' => 'T_EVAL',
			),
			'exit: statement'                        => array(
				'testMarker'        => '/* testExitIsKeyword */',
				'expectedTokenType' => 'T_EXIT',
			),
			'isset'                                  => array(
				'testMarker'        => '/* testIssetIsKeyword */',
				'expectedTokenType' => 'T_ISSET',
			),
			'unset'                                  => array(
				'testMarker'        => '/* testUnsetIsKeyword */',
				'expectedTokenType' => 'T_UNSET',
			),
			'\\die: statement (fully qualified)'     => array(
				'testMarker'        => '/* testFullyQualifiedDieIsKeyword */',
				'expectedTokenType' => 'T_EXIT',
			),
			'\\exit: statement (fully qualified)'    => array(
				'testMarker'        => '/* testFullyQualifiedExitIsKeyword */',
				'expectedTokenType' => 'T_EXIT',
			),

			'include'                                => array(
				'testMarker'        => '/* testIncludeIsKeyword */',
				'expectedTokenType' => 'T_INCLUDE',
			),
			'include_once'                           => array(
				'testMarker'        => '/* testIncludeOnceIsKeyword */',
				'expectedTokenType' => 'T_INCLUDE_ONCE',
			),
			'require'                                => array(
				'testMarker'        => '/* testRequireIsKeyword */',
				'expectedTokenType' => 'T_REQUIRE',
			),
			'require_once'                           => array(
				'testMarker'        => '/* testRequireOnceIsKeyword */',
				'expectedTokenType' => 'T_REQUIRE_ONCE',
			),

			'list'                                   => array(
				'testMarker'        => '/* testListIsKeyword */',
				'expectedTokenType' => 'T_LIST',
			),
			'goto'                                   => array(
				'testMarker'        => '/* testGotoIsKeyword */',
				'expectedTokenType' => 'T_GOTO',
			),
			'match'                                  => array(
				'testMarker'        => '/* testMatchIsKeyword */',
				'expectedTokenType' => 'T_MATCH',
			),
			'default: in match expression'           => array(
				'testMarker'        => '/* testMatchDefaultIsKeyword */',
				'expectedTokenType' => 'T_MATCH_DEFAULT',
			),
			'fn'                                     => array(
				'testMarker'        => '/* testFnIsKeyword */',
				'expectedTokenType' => 'T_FN',
			),

			'yield'                                  => array(
				'testMarker'        => '/* testYieldIsKeyword */',
				'expectedTokenType' => 'T_YIELD',
			),
			'yield from'                             => array(
				'testMarker'        => '/* testYieldFromIsKeyword */',
				'expectedTokenType' => 'T_YIELD_FROM',
			),

			'declare'                                => array(
				'testMarker'        => '/* testDeclareIsKeyword */',
				'expectedTokenType' => 'T_DECLARE',
			),
			'enddeclare'                             => array(
				'testMarker'        => '/* testEndDeclareIsKeyword */',
				'expectedTokenType' => 'T_ENDDECLARE',
			),

			'and: in if'                             => array(
				'testMarker'        => '/* testAndIsKeyword */',
				'expectedTokenType' => 'T_LOGICAL_AND',
			),
			'or: in if'                              => array(
				'testMarker'        => '/* testOrIsKeyword */',
				'expectedTokenType' => 'T_LOGICAL_OR',
			),
			'xor: in if'                             => array(
				'testMarker'        => '/* testXorIsKeyword */',
				'expectedTokenType' => 'T_LOGICAL_XOR',
			),

			'class: anon class declaration'          => array(
				'testMarker'        => '/* testAnonymousClassIsKeyword */',
				'expectedTokenType' => 'T_ANON_CLASS',
			),
			'extends: anon class declaration'        => array(
				'testMarker'        => '/* testExtendsInAnonymousClassIsKeyword */',
				'expectedTokenType' => 'T_EXTENDS',
			),
			'implements: anon class declaration'     => array(
				'testMarker'        => '/* testImplementsInAnonymousClassIsKeyword */',
				'expectedTokenType' => 'T_IMPLEMENTS',
			),
			'static: class instantiation'            => array(
				'testMarker'        => '/* testClassInstantiationStaticIsKeyword */',
				'expectedTokenType' => 'T_STATIC',
			),
			'namespace: operator'                    => array(
				'testMarker'        => '/* testNamespaceInNameIsKeyword */',
				'expectedTokenType' => 'T_NAMESPACE',
			),

			'static: closure declaration'            => array(
				'testMarker'        => '/* testStaticIsKeywordBeforeClosure */',
				'expectedTokenType' => 'T_STATIC',
			),
			'static: parameter type (illegal)'       => array(
				'testMarker'        => '/* testStaticIsKeywordWhenParamType */',
				'expectedTokenType' => 'T_STATIC',
			),
			'static: arrow function declaration'     => array(
				'testMarker'        => '/* testStaticIsKeywordBeforeArrow */',
				'expectedTokenType' => 'T_STATIC',
			),
			'static: return type for arrow function' => array(
				'testMarker'        => '/* testStaticIsKeywordWhenReturnType */',
				'expectedTokenType' => 'T_STATIC',
			),
			'static: property modifier before DNF'   => array(
				'testMarker'        => '/* testStaticIsKeywordPropertyModifierBeforeDNF */',
				'expectedTokenType' => 'T_STATIC',
			),
		);
	}//end dataKeywords()
}//end class
