<?php
/**
 * Tests for the \PHP_CodeSniffer\Files\File::findImplementedInterfaceNames method.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Files\File;

use PHP_CodeSniffer\Tests\Core\AbstractMethodUnitTest;

/**
 * Tests for the \PHP_CodeSniffer\Files\File::findImplementedInterfaceNames method.
 *
 * @covers \PHP_CodeSniffer\Files\File::findImplementedInterfaceNames
 */
final class FindImplementedInterfaceNamesTest extends AbstractMethodUnitTest {



	/**
	 * Test getting a `false` result when a non-existent token is passed.
	 *
	 * @return void
	 */
	public function testNonExistentToken() {
		$result = self::$phpcsFile->findImplementedInterfaceNames( 100000 );
		$this->assertFalse( $result );
	}//end testNonExistentToken()


	/**
	 * Test getting a `false` result when a token other than one of the supported tokens is passed.
	 *
	 * @return void
	 */
	public function testNotAClass() {
		$token  = $this->getTargetToken( '/* testNotAClass */', array( T_FUNCTION ) );
		$result = self::$phpcsFile->findImplementedInterfaceNames( $token );
		$this->assertFalse( $result );
	}//end testNotAClass()


	/**
	 * Test retrieving the name(s) of the interfaces being implemented by a class.
	 *
	 * @param string              $identifier Comment which precedes the test case.
	 * @param array<string>|false $expected   Expected function output.
	 *
	 * @dataProvider dataImplementedInterface
	 *
	 * @return void
	 */
	public function testFindImplementedInterfaceNames( $identifier, $expected ) {
		$OOToken = $this->getTargetToken( $identifier, array( T_CLASS, T_ANON_CLASS, T_INTERFACE, T_ENUM ) );
		$result  = self::$phpcsFile->findImplementedInterfaceNames( $OOToken );
		$this->assertSame( $expected, $result );
	}//end testFindImplementedInterfaceNames()


	/**
	 * Data provider for the FindImplementedInterfaceNames test.
	 *
	 * @see testFindImplementedInterfaceNames()
	 *
	 * @return array<string, array<string, string|array<string>|false>>
	 */
	public static function dataImplementedInterface() {
		return array(
			'interface declaration, no implements'        => array(
				'identifier' => '/* testPlainInterface */',
				'expected'   => false,
			),
			'class does not implement'                    => array(
				'identifier' => '/* testNonImplementedClass */',
				'expected'   => false,
			),
			'class implements single interface, unqualified' => array(
				'identifier' => '/* testClassImplementsSingle */',
				'expected'   => array(
					'testFIINInterface',
				),
			),
			'class implements multiple interfaces'        => array(
				'identifier' => '/* testClassImplementsMultiple */',
				'expected'   => array(
					'testFIINInterface',
					'testFIINInterface2',
				),
			),
			'class implements single interface, fully qualified' => array(
				'identifier' => '/* testImplementsFullyQualified */',
				'expected'   => array(
					'\PHP_CodeSniffer\Tests\Core\File\testFIINInterface',
				),
			),
			'class implements single interface, partially qualified' => array(
				'identifier' => '/* testImplementsPartiallyQualified */',
				'expected'   => array(
					'Core\File\RelativeInterface',
				),
			),
			'class extends and implements'                => array(
				'identifier' => '/* testClassThatExtendsAndImplements */',
				'expected'   => array(
					'InterfaceA',
					'\NameSpaced\Cat\InterfaceB',
				),
			),
			'class implements and extends'                => array(
				'identifier' => '/* testClassThatImplementsAndExtends */',
				'expected'   => array(
					'\InterfaceA',
					'InterfaceB',
				),
			),
			'enum does not implement'                     => array(
				'identifier' => '/* testBackedEnumWithoutImplements */',
				'expected'   => false,
			),
			'enum implements single interface, unqualified' => array(
				'identifier' => '/* testEnumImplementsSingle */',
				'expected'   => array(
					'Colorful',
				),
			),
			'enum implements multiple interfaces, unqualified + fully qualified' => array(
				'identifier' => '/* testBackedEnumImplementsMulti */',
				'expected'   => array(
					'Colorful',
					'\Deck',
				),
			),
			'anon class implements single interface, unqualified' => array(
				'identifier' => '/* testAnonClassImplementsSingle */',
				'expected'   => array(
					'testFIINInterface',
				),
			),
			'parse error - implements keyword, but no interface name' => array(
				'identifier' => '/* testMissingImplementsName */',
				'expected'   => false,
			),
			'parse error - live coding - no curly braces' => array(
				'identifier' => '/* testParseError */',
				'expected'   => false,
			),
		);
	}//end dataImplementedInterface()
}//end class
