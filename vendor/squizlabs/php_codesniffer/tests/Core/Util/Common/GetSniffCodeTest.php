<?php
/**
 * Tests for the \PHP_CodeSniffer\Util\Common::getSniffCode() method.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Util\Common;

use PHP_CodeSniffer\Util\Common;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the \PHP_CodeSniffer\Util\Common::getSniffCode() method.
 *
 * @covers \PHP_CodeSniffer\Util\Common::getSniffCode
 */
final class GetSniffCodeTest extends TestCase {



	/**
	 * Test receiving an expected exception when the $sniffClass parameter is not passed a string value or is passed an empty string.
	 *
	 * @param mixed $input NOT a fully qualified sniff class name.
	 *
	 * @dataProvider dataGetSniffCodeThrowsExceptionOnInvalidInput
	 *
	 * @return void
	 */
	public function testGetSniffCodeThrowsExceptionOnInvalidInput( $input ) {
		$exception = 'InvalidArgumentException';
		$message   = 'The $sniffClass parameter must be a non-empty string';

		if ( method_exists( $this, 'expectException' ) === true ) {
			// PHPUnit 5+.
			$this->expectException( $exception );
			$this->expectExceptionMessage( $message );
		} else {
			// PHPUnit 4.
			$this->setExpectedException( $exception, $message );
		}

		Common::getSniffCode( $input );
	}//end testGetSniffCodeThrowsExceptionOnInvalidInput()


	/**
	 * Data provider.
	 *
	 * @see testGetSniffCodeThrowsExceptionOnInvalidInput()
	 *
	 * @return array<string, array<mixed>>
	 */
	public static function dataGetSniffCodeThrowsExceptionOnInvalidInput() {
		return array(
			'Class name is not a string' => array( true ),
			'Class name is empty'        => array( '' ),
		);
	}//end dataGetSniffCodeThrowsExceptionOnInvalidInput()


	/**
	 * Test receiving an expected exception when the $sniffClass parameter is not passed a value which
	 * could be a fully qualified sniff(test) class name.
	 *
	 * @param string $input String input which can not be a fully qualified sniff(test) class name.
	 *
	 * @dataProvider dataGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass
	 *
	 * @return void
	 */
	public function testGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass( $input ) {
		$exception = 'InvalidArgumentException';
		$message   = 'The $sniffClass parameter was not passed a fully qualified sniff(test) class name. Received:';

		if ( method_exists( $this, 'expectException' ) === true ) {
			// PHPUnit 5+.
			$this->expectException( $exception );
			$this->expectExceptionMessage( $message );
		} else {
			// PHPUnit 4.
			$this->setExpectedException( $exception, $message );
		}

		Common::getSniffCode( $input );
	}//end testGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass()


	/**
	 * Data provider.
	 *
	 * @see testGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass() {
		return array(
			'Unqualified class name'                       => array( 'ClassName' ),
			'Fully qualified class name, not enough parts' => array( 'Fully\\Qualified\\ClassName' ),
			'Fully qualified class name, doesn\'t end on Sniff or UnitTest' => array( 'Fully\\Sniffs\\Qualified\\ClassName' ),
		);
	}//end dataGetSniffCodeThrowsExceptionOnInputWhichIsNotASniffTestClass()


	/**
	 * Test transforming a sniff class name to a sniff code.
	 *
	 * @param string $fqnClass A fully qualified sniff class name.
	 * @param string $expected Expected function output.
	 *
	 * @dataProvider dataGetSniffCode
	 *
	 * @return void
	 */
	public function testGetSniffCode( $fqnClass, $expected ) {
		$this->assertSame( $expected, Common::getSniffCode( $fqnClass ) );
	}//end testGetSniffCode()


	/**
	 * Data provider.
	 *
	 * @see testGetSniffCode()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataGetSniffCode() {
		return array(
			'PHPCS native sniff'                 => array(
				'fqnClass' => 'PHP_CodeSniffer\\Standards\\Generic\\Sniffs\\Arrays\\ArrayIndentSniff',
				'expected' => 'Generic.Arrays.ArrayIndent',
			),
			'Class is a PHPCS native test class' => array(
				'fqnClass' => 'PHP_CodeSniffer\\Standards\\Generic\\Tests\\Arrays\\ArrayIndentUnitTest',
				'expected' => 'Generic.Arrays.ArrayIndent',
			),
			'Sniff in external standard without namespace prefix' => array(
				'fqnClass' => 'MyStandard\\Sniffs\\PHP\\MyNameSniff',
				'expected' => 'MyStandard.PHP.MyName',
			),
			'Test in external standard without namespace prefix' => array(
				'fqnClass' => 'MyStandard\\Tests\\PHP\\MyNameUnitTest',
				'expected' => 'MyStandard.PHP.MyName',
			),
			'Sniff in external standard with namespace prefix' => array(
				'fqnClass' => 'Vendor\\Package\\MyStandard\\Sniffs\\Category\\AnalyzeMeSniff',
				'expected' => 'MyStandard.Category.AnalyzeMe',
			),
			'Test in external standard with namespace prefix' => array(
				'fqnClass' => 'Vendor\\Package\\MyStandard\\Tests\\Category\\AnalyzeMeUnitTest',
				'expected' => 'MyStandard.Category.AnalyzeMe',
			),

			/*
			 * These are not valid sniff codes and is an undesirable result, but can't be helped
			 * as changing this would be a BC-break.
			 * Supporting these to allow for <rule> tags directly including sniff files.
			 * See: https://github.com/PHPCSStandards/PHP_CodeSniffer/issues/675
			 */

			'Fully qualified class name, ends on Sniff, but isn\'t' => array(
				'fqnClass' => 'Fully\\Sniffs\\AbstractSomethingSniff',
				'expected' => '.Sniffs.AbstractSomething',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [1]' => array(
				'fqnClass' => 'CheckMeSniff',
				'expected' => '..CheckMe',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [2]' => array(
				'fqnClass' => 'CompanyName\\CheckMeSniff',
				'expected' => '.CompanyName.CheckMe',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [3]' => array(
				'fqnClass' => 'CompanyName\\Sniffs\\CheckMeSniff',
				'expected' => '.Sniffs.CheckMe',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [4]' => array(
				'fqnClass' => 'CompanyName\\CustomSniffs\\Whatever\\CheckMeSniff',
				'expected' => 'CompanyName.Whatever.CheckMe',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [5]' => array(
				'fqnClass' => 'CompanyName\\Sniffs\\Category\\Sniff',
				'expected' => 'CompanyName.Category.',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [6]' => array(
				'fqnClass' => 'CompanyName\\Tests\\Category\\UnitTest',
				'expected' => 'CompanyName.Category.',
			),
			'Sniff provided via file include and doesn\'t comply with naming conventions [7]' => array(
				'fqnClass' => 'Sniffs\\Category\\NamedSniff',
				'expected' => '.Category.Named',
			),
		);
	}//end dataGetSniffCode()
}//end class
