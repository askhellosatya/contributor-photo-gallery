<?php
/**
 * Tests for the \PHP_CodeSniffer\Files\File::findExtendedClassName method.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Files\File;

use PHP_CodeSniffer\Tests\Core\AbstractMethodUnitTest;

/**
 * Tests for the \PHP_CodeSniffer\Files\File::findExtendedClassName method.
 *
 * @covers \PHP_CodeSniffer\Files\File::findExtendedClassName
 */
final class FindExtendedClassNameTest extends AbstractMethodUnitTest {



	/**
	 * Test getting a `false` result when a non-existent token is passed.
	 *
	 * @return void
	 */
	public function testNonExistentToken() {
		$result = self::$phpcsFile->findExtendedClassName( 100000 );
		$this->assertFalse( $result );
	}//end testNonExistentToken()


	/**
	 * Test getting a `false` result when a token other than one of the supported tokens is passed.
	 *
	 * @return void
	 */
	public function testNotAClass() {
		$token  = $this->getTargetToken( '/* testNotAClass */', array( T_FUNCTION ) );
		$result = self::$phpcsFile->findExtendedClassName( $token );
		$this->assertFalse( $result );
	}//end testNotAClass()


	/**
	 * Test retrieving the name of the class being extended by another class
	 * (or interface).
	 *
	 * @param string       $identifier Comment which precedes the test case.
	 * @param string|false $expected   Expected function output.
	 *
	 * @dataProvider dataExtendedClass
	 *
	 * @return void
	 */
	public function testFindExtendedClassName( $identifier, $expected ) {
		$OOToken = $this->getTargetToken( $identifier, array( T_CLASS, T_ANON_CLASS, T_INTERFACE ) );
		$result  = self::$phpcsFile->findExtendedClassName( $OOToken );
		$this->assertSame( $expected, $result );
	}//end testFindExtendedClassName()


	/**
	 * Data provider for the FindExtendedClassName test.
	 *
	 * @see testFindExtendedClassName()
	 *
	 * @return array<string, array<string, string|false>>
	 */
	public static function dataExtendedClass() {
		return array(
			'class does not extend'                       => array(
				'identifier' => '/* testNonExtendedClass */',
				'expected'   => false,
			),
			'class extends unqualified class'             => array(
				'identifier' => '/* testExtendsUnqualifiedClass */',
				'expected'   => 'testFECNClass',
			),
			'class extends fully qualified class'         => array(
				'identifier' => '/* testExtendsFullyQualifiedClass */',
				'expected'   => '\PHP_CodeSniffer\Tests\Core\File\testFECNClass',
			),
			'class extends partially qualified class'     => array(
				'identifier' => '/* testExtendsPartiallyQualifiedClass */',
				'expected'   => 'Core\File\RelativeClass',
			),
			'interface does not extend'                   => array(
				'identifier' => '/* testNonExtendedInterface */',
				'expected'   => false,
			),
			'interface extends unqualified interface'     => array(
				'identifier' => '/* testInterfaceExtendsUnqualifiedInterface */',
				'expected'   => 'testFECNInterface',
			),
			'interface extends fully qualified interface' => array(
				'identifier' => '/* testInterfaceExtendsFullyQualifiedInterface */',
				'expected'   => '\PHP_CodeSniffer\Tests\Core\File\testFECNInterface',
			),
			'anon class extends unqualified class'        => array(
				'identifier' => '/* testExtendedAnonClass */',
				'expected'   => 'testFECNExtendedAnonClass',
			),
			'class does not extend but contains anon class which extends' => array(
				'identifier' => '/* testNestedExtendedClass */',
				'expected'   => false,
			),
			'anon class extends, nested in non-extended class' => array(
				'identifier' => '/* testNestedExtendedAnonClass */',
				'expected'   => 'testFECNAnonClass',
			),
			'class extends and implements'                => array(
				'identifier' => '/* testClassThatExtendsAndImplements */',
				'expected'   => 'testFECNClass',
			),
			'class implements and extends'                => array(
				'identifier' => '/* testClassThatImplementsAndExtends */',
				'expected'   => 'testFECNClass',
			),
			'interface extends multiple interfaces (not supported)' => array(
				'identifier' => '/* testInterfaceMultiExtends */',
				'expected'   => '\Package\FooInterface',
			),
			'readonly anon class extends fully qualified class' => array(
				'identifier' => '/* testExtendedReadonlyAnonClass */',
				'expected'   => '\Fully\Qualified\MyClass',
			),
			'anon class with attribute extends partially qualified class' => array(
				'identifier' => '/* testExtendedAnonClassWithAttributes */',
				'expected'   => 'Partially\Qualified\MyClass',
			),
			'parse error - extends keyword, but no class name' => array(
				'identifier' => '/* testMissingExtendsName */',
				'expected'   => false,
			),
			'parse error - live coding - no curly braces' => array(
				'identifier' => '/* testParseError */',
				'expected'   => false,
			),
		);
	}//end dataExtendedClass()
}//end class
