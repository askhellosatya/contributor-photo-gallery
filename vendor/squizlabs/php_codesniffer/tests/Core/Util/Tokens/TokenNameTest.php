<?php
/**
 * Tests for the \PHP_CodeSniffer\Util\Tokens::tokenName() method.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Util\Tokens;

use PHP_CodeSniffer\Util\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the \PHP_CodeSniffer\Util\Tokens::tokenName() method.
 *
 * @covers \PHP_CodeSniffer\Util\Tokens::tokenName
 */
final class TokenNameTest extends TestCase {



	/**
	 * Test the method.
	 *
	 * @param int|string $tokenCode The PHP/PHPCS token code to get the name for.
	 * @param string     $expected  The expected token name.
	 *
	 * @dataProvider dataTokenName
	 * @dataProvider dataPolyfilledPHPNativeTokens
	 *
	 * @return void
	 */
	public function testTokenName( $tokenCode, $expected ) {
		$this->assertSame( $expected, Tokens::tokenName( $tokenCode ) );
	}//end testTokenName()


	/**
	 * Data provider.
	 *
	 * @return array<string, array<string, int|string>>
	 */
	public static function dataTokenName() {
		return array(
			'PHP native token: T_ECHO'            => array(
				'tokenCode' => T_ECHO,
				'expected'  => 'T_ECHO',
			),
			'PHP native token: T_FUNCTION'        => array(
				'tokenCode' => T_FUNCTION,
				'expected'  => 'T_FUNCTION',
			),
			'PHPCS native token: T_CLOSURE'       => array(
				'tokenCode' => T_CLOSURE,
				'expected'  => 'T_CLOSURE',
			),
			'PHPCS native token: T_STRING_CONCAT' => array(
				'tokenCode' => T_STRING_CONCAT,
				'expected'  => 'T_STRING_CONCAT',
			),

			// Document the current behaviour for invalid input.
			// This behaviour is subject to change.
			'Non-token integer passed'            => array(
				'tokenCode' => 100000,
				'expected'  => 'UNKNOWN',
			),
			'Non-token string passed'             => array(
				'tokenCode' => 'something',
				'expected'  => 'ing',
			),
		);
	}//end dataTokenName()


	/**
	 * Data provider.
	 *
	 * @return array<string, array<string, int|string>>
	 */
	public static function dataPolyfilledPHPNativeTokens() {
		return array(
			'PHP 5.5 native token, polyfilled: T_FINALLY'  => array(
				'tokenCode' => T_FINALLY,
				'expected'  => 'T_FINALLY',
			),
			'PHP 5.5 native token, polyfilled: T_YIELD'    => array(
				'tokenCode' => T_YIELD,
				'expected'  => 'T_YIELD',
			),

			'PHP 5.6 native token, polyfilled: T_ELLIPSIS' => array(
				'tokenCode' => T_ELLIPSIS,
				'expected'  => 'T_ELLIPSIS',
			),
			'PHP 5.6 native token, polyfilled: T_POW'      => array(
				'tokenCode' => T_POW,
				'expected'  => 'T_POW',
			),
			'PHP 5.6 native token, polyfilled: T_POW_EQUAL' => array(
				'tokenCode' => T_POW_EQUAL,
				'expected'  => 'T_POW_EQUAL',
			),

			'PHP 7.0 native token, polyfilled: T_SPACESHIP' => array(
				'tokenCode' => T_SPACESHIP,
				'expected'  => 'T_SPACESHIP',
			),
			'PHP 7.0 native token, polyfilled: T_COALESCE' => array(
				'tokenCode' => T_COALESCE,
				'expected'  => 'T_COALESCE',
			),
			'PHP 7.0 native token, polyfilled: T_YIELD_FROM' => array(
				'tokenCode' => T_YIELD_FROM,
				'expected'  => 'T_YIELD_FROM',
			),

			'PHP 7.4 native token, polyfilled: T_COALESCE_EQUAL' => array(
				'tokenCode' => T_COALESCE_EQUAL,
				'expected'  => 'T_COALESCE_EQUAL',
			),
			'PHP 7.4 native token, polyfilled: T_BAD_CHARACTER' => array(
				'tokenCode' => T_BAD_CHARACTER,
				'expected'  => 'T_BAD_CHARACTER',
			),
			'PHP 7.4 native token, polyfilled: T_FN'       => array(
				'tokenCode' => T_FN,
				'expected'  => 'T_FN',
			),

			'PHP 8.0 native token, polyfilled: T_NULLSAFE_OBJECT_OPERATOR' => array(
				'tokenCode' => T_NULLSAFE_OBJECT_OPERATOR,
				'expected'  => 'T_NULLSAFE_OBJECT_OPERATOR',
			),
			'PHP 8.0 native token, polyfilled: T_NAME_QUALIFIED' => array(
				'tokenCode' => T_NAME_QUALIFIED,
				'expected'  => 'T_NAME_QUALIFIED',
			),
			'PHP 8.0 native token, polyfilled: T_NAME_FULLY_QUALIFIED' => array(
				'tokenCode' => T_NAME_FULLY_QUALIFIED,
				'expected'  => 'T_NAME_FULLY_QUALIFIED',
			),
			'PHP 8.0 native token, polyfilled: T_NAME_RELATIVE' => array(
				'tokenCode' => T_NAME_RELATIVE,
				'expected'  => 'T_NAME_RELATIVE',
			),
			'PHP 8.0 native token, polyfilled: T_MATCH'    => array(
				'tokenCode' => T_MATCH,
				'expected'  => 'T_MATCH',
			),
			'PHP 8.0 native token, polyfilled: T_ATTRIBUTE' => array(
				'tokenCode' => T_ATTRIBUTE,
				'expected'  => 'T_ATTRIBUTE',
			),

			'PHP 8.1 native token, polyfilled: T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG' => array(
				'tokenCode' => T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG,
				'expected'  => 'T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG',
			),
			'PHP 8.1 native token, polyfilled: T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG' => array(
				'tokenCode' => T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG,
				'expected'  => 'T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG',
			),
			'PHP 8.1 native token, polyfilled: T_READONLY' => array(
				'tokenCode' => T_READONLY,
				'expected'  => 'T_READONLY',
			),
			'PHP 8.1 native token, polyfilled: T_ENUM'     => array(
				'tokenCode' => T_ENUM,
				'expected'  => 'T_ENUM',
			),

			'PHP 8.4 native token, polyfilled: T_PUBLIC_SET' => array(
				'tokenCode' => T_PUBLIC_SET,
				'expected'  => 'T_PUBLIC_SET',
			),
			'PHP 8.4 native token, polyfilled: T_PROTECTED_SET' => array(
				'tokenCode' => T_PROTECTED_SET,
				'expected'  => 'T_PROTECTED_SET',
			),
			'PHP 8.4 native token, polyfilled: T_PRIVATE_SET' => array(
				'tokenCode' => T_PRIVATE_SET,
				'expected'  => 'T_PRIVATE_SET',
			),
		);
	}//end dataPolyfilledPHPNativeTokens()
}//end class
