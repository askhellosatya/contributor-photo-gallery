<?php
/**
 * Tests for the \PHP_CodeSniffer\Files\File:getClassProperties method.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2022 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Files\File;

use PHP_CodeSniffer\Tests\Core\AbstractMethodUnitTest;

/**
 * Tests for the \PHP_CodeSniffer\Files\File:getClassProperties method.
 *
 * @covers \PHP_CodeSniffer\Files\File::getClassProperties
 */
final class GetClassPropertiesTest extends AbstractMethodUnitTest {



	/**
	 * Test receiving an expected exception when a non class token is passed.
	 *
	 * @param string     $testMarker The comment which prefaces the target token in the test file.
	 * @param int|string $tokenType  The type of token to look for after the marker.
	 *
	 * @dataProvider dataNotAClassException
	 *
	 * @return void
	 */
	public function testNotAClassException( $testMarker, $tokenType ) {
		$this->expectRunTimeException( '$stackPtr must be of type T_CLASS' );

		$target = $this->getTargetToken( $testMarker, $tokenType );
		self::$phpcsFile->getClassProperties( $target );
	}//end testNotAClassException()


	/**
	 * Data provider.
	 *
	 * @see testNotAClassException() For the array format.
	 *
	 * @return array<string, array<string, string|int>>
	 */
	public static function dataNotAClassException() {
		return array(
			'interface'  => array(
				'testMarker' => '/* testNotAClass */',
				'tokenType'  => T_INTERFACE,
			),
			'anon-class' => array(
				'testMarker' => '/* testAnonClass */',
				'tokenType'  => T_ANON_CLASS,
			),
			'enum'       => array(
				'testMarker' => '/* testEnum */',
				'tokenType'  => T_ENUM,
			),
		);
	}//end dataNotAClassException()


	/**
	 * Test retrieving the properties for a class declaration.
	 *
	 * @param string              $testMarker The comment which prefaces the target token in the test file.
	 * @param array<string, bool> $expected   Expected function output.
	 *
	 * @dataProvider dataGetClassProperties
	 *
	 * @return void
	 */
	public function testGetClassProperties( $testMarker, $expected ) {
		$class  = $this->getTargetToken( $testMarker, T_CLASS );
		$result = self::$phpcsFile->getClassProperties( $class );
		$this->assertSame( $expected, $result );
	}//end testGetClassProperties()


	/**
	 * Data provider.
	 *
	 * @see testGetClassProperties() For the array format.
	 *
	 * @return array<string, array<string, string|array<string, bool|int>>>
	 */
	public static function dataGetClassProperties() {
		return array(
			'no-properties'               => array(
				'testMarker' => '/* testClassWithoutProperties */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => false,
					'is_readonly' => false,
				),
			),
			'abstract'                    => array(
				'testMarker' => '/* testAbstractClass */',
				'expected'   => array(
					'is_abstract' => true,
					'is_final'    => false,
					'is_readonly' => false,
				),
			),
			'final'                       => array(
				'testMarker' => '/* testFinalClass */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => true,
					'is_readonly' => false,
				),
			),
			'readonly'                    => array(
				'testMarker' => '/* testReadonlyClass */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => false,
					'is_readonly' => true,
				),
			),
			'final-readonly'              => array(
				'testMarker' => '/* testFinalReadonlyClass */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => true,
					'is_readonly' => true,
				),
			),
			'readonly-final'              => array(
				'testMarker' => '/* testReadonlyFinalClass */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => true,
					'is_readonly' => true,
				),
			),
			'abstract-readonly'           => array(
				'testMarker' => '/* testAbstractReadonlyClass */',
				'expected'   => array(
					'is_abstract' => true,
					'is_final'    => false,
					'is_readonly' => true,
				),
			),
			'readonly-abstract'           => array(
				'testMarker' => '/* testReadonlyAbstractClass */',
				'expected'   => array(
					'is_abstract' => true,
					'is_final'    => false,
					'is_readonly' => true,
				),
			),
			'comments-and-new-lines'      => array(
				'testMarker' => '/* testWithCommentsAndNewLines */',
				'expected'   => array(
					'is_abstract' => true,
					'is_final'    => false,
					'is_readonly' => false,
				),
			),
			'no-properties-with-docblock' => array(
				'testMarker' => '/* testWithDocblockWithoutProperties */',
				'expected'   => array(
					'is_abstract' => false,
					'is_final'    => false,
					'is_readonly' => false,
				),
			),
			'abstract-final-parse-error'  => array(
				'testMarker' => '/* testParseErrorAbstractFinal */',
				'expected'   => array(
					'is_abstract' => true,
					'is_final'    => true,
					'is_readonly' => false,
				),
			),
		);
	}//end dataGetClassProperties()
}//end class
