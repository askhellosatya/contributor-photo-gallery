<?php
/**
 * Tests for the \PHP_CodeSniffer\Util\Common::suggestType() method.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2019 Juliette Reinders Folmer. All rights reserved.
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Util\Common;

use PHP_CodeSniffer\Util\Common;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the \PHP_CodeSniffer\Util\Common::suggestType() method.
 *
 * @covers \PHP_CodeSniffer\Util\Common::suggestType
 */
final class SuggestTypeTest extends TestCase {



	/**
	 * Test passing an empty type to the suggestType() method.
	 *
	 * @return void
	 */
	public function testSuggestTypeEmpty() {
		$this->assertSame( '', Common::suggestType( '' ) );
	}//end testSuggestTypeEmpty()


	/**
	 * Test passing one of the allowed types to the suggestType() method.
	 *
	 * @param string $varType The type.
	 *
	 * @dataProvider dataSuggestTypeAllowedType
	 *
	 * @return void
	 */
	public function testSuggestTypeAllowedType( $varType ) {
		$result = Common::suggestType( $varType );
		$this->assertSame( $varType, $result );
	}//end testSuggestTypeAllowedType()


	/**
	 * Data provider.
	 *
	 * @see testSuggestTypeAllowedType()
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataSuggestTypeAllowedType() {
		$data = array();
		foreach ( Common::$allowedTypes as $type ) {
			$data[ 'Type: ' . $type ] = array( $type );
		}

		return $data;
	}//end dataSuggestTypeAllowedType()


	/**
	 * Test passing one of the allowed types in the wrong case to the suggestType() method.
	 *
	 * @param string $varType  The type found.
	 * @param string $expected Expected suggested type.
	 *
	 * @dataProvider dataSuggestTypeAllowedTypeWrongCase
	 *
	 * @return void
	 */
	public function testSuggestTypeAllowedTypeWrongCase( $varType, $expected ) {
		$result = Common::suggestType( $varType );
		$this->assertSame( $expected, $result );
	}//end testSuggestTypeAllowedTypeWrongCase()


	/**
	 * Data provider.
	 *
	 * @see testSuggestTypeAllowedTypeWrongCase()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataSuggestTypeAllowedTypeWrongCase() {
		$data = array();
		foreach ( Common::$allowedTypes as $type ) {
			$data[ 'Mixed case: ' . $type ] = array(
				'varType'  => ucfirst( $type ),
				'expected' => $type,
			);
			$data[ 'Uppercase: ' . $type ]  = array(
				'varType'  => strtoupper( $type ),
				'expected' => $type,
			);
		}

		return $data;
	}//end dataSuggestTypeAllowedTypeWrongCase()


	/**
	 * Test the suggestType() method for all other cases.
	 *
	 * @param string $varType  The type found.
	 * @param string $expected Expected suggested type.
	 *
	 * @dataProvider dataSuggestTypeOther
	 *
	 * @return void
	 */
	public function testSuggestTypeOther( $varType, $expected ) {
		$result = Common::suggestType( $varType );
		$this->assertSame( $expected, $result );
	}//end testSuggestTypeOther()


	/**
	 * Data provider.
	 *
	 * @see testSuggestTypeOther()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataSuggestTypeOther() {
		return array(
			// Short forms.
			'Short form type: bool, lowercase'     => array(
				'varType'  => 'bool',
				'expected' => 'boolean',
			),
			'Short form type: bool, uppercase'     => array(
				'varType'  => 'BOOL',
				'expected' => 'boolean',
			),
			'Short form type: double, lowercase'   => array(
				'varType'  => 'double',
				'expected' => 'float',
			),
			'Short form type: real, mixed case'    => array(
				'varType'  => 'Real',
				'expected' => 'float',
			),
			'Short form type: double, mixed case'  => array(
				'varType'  => 'DoUbLe',
				'expected' => 'float',
			),
			'Short form type: int, lowercase'      => array(
				'varType'  => 'int',
				'expected' => 'integer',
			),
			'Short form type: int, uppercase'      => array(
				'varType'  => 'INT',
				'expected' => 'integer',
			),

			// Array types.
			'Array type: mixed case keyword, empty parentheses' => array(
				'varType'  => 'Array()',
				'expected' => 'array',
			),
			'Array type: short form type as value within the parentheses' => array(
				'varType'  => 'array(real)',
				'expected' => 'array(float)',
			),
			'Array type: short form type as key within the parentheses' => array(
				'varType'  => 'array(int => object)',
				'expected' => 'array(integer => object)',
			),
			'Array type: valid specification'      => array(
				'varType'  => 'array(integer => array(string => resource))',
				'expected' => 'array(integer => array(string => resource))',
			),
			'Array type: short form + uppercase types within the parentheses' => array(
				'varType'  => 'ARRAY(BOOL => DOUBLE)',
				'expected' => 'array(boolean => float)',
			),
			'Array type: no space around the arrow within the parentheses' => array(
				'varType'  => 'array(string=>resource)',
				'expected' => 'array(string => resource)',
			),

			// Incomplete array type.
			'Array type: incomplete specification' => array(
				'varType'  => 'array(int =>',
				'expected' => 'array',
			),

			// Custom types are returned unchanged.
			'Unknown type: "<string> => <int>"'    => array(
				'varType'  => '<string> => <int>',
				'expected' => '<string> => <int>',
			),
			'Unknown type: "string[]"'             => array(
				'varType'  => 'string[]',
				'expected' => 'string[]',
			),
			'Unknown type: "\DateTime"'            => array(
				'varType'  => '\DateTime',
				'expected' => '\DateTime',
			),
		);
	}//end dataSuggestTypeOther()
}//end class
