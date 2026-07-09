<?php
/**
 * Tests the support of PHP 8.4 asymmetric visibility.
 *
 * @author    Daniel Scherzer <daniel.e.scherzer@gmail.com>
 * @copyright 2025 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;

/**
 * Tests the support of PHP 8.4 asymmetric visibility.
 *
 * @covers PHP_CodeSniffer\Tokenizers\PHP::processAdditional
 */
final class BackfillAsymmetricVisibilityTest extends AbstractTokenizerTestCase {



	/**
	 * Test that the asymmetric visibility keywords are tokenized as such.
	 *
	 * @param string $testMarker  The comment which prefaces the target token in the test file.
	 * @param string $testType    The expected token type.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataAsymmetricVisibility
	 *
	 * @return void
	 */
	public function testAsymmetricVisibility( $testMarker, $testType, $testContent ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken(
			$testMarker,
			array(
				T_PUBLIC_SET,
				T_PROTECTED_SET,
				T_PRIVATE_SET,
			)
		);
		$tokenArray = $tokens[ $target ];

		$this->assertSame(
			$testType,
			$tokenArray['type'],
			'Token tokenized as ' . $tokenArray['type'] . ' (type)'
		);
		$this->assertSame(
			constant( $testType ),
			$tokenArray['code'],
			'Token tokenized as ' . $tokenArray['type'] . ' (code)'
		);
		$this->assertSame(
			$testContent,
			$tokenArray['content'],
			'Token tokenized as ' . $tokenArray['type'] . ' (content)'
		);
	}//end testAsymmetricVisibility()


	/**
	 * Data provider.
	 *
	 * @see testAsymmetricVisibility()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataAsymmetricVisibility() {
		return array(
			// Normal property declarations.
			'property, public set, no read visibility, lowercase'      => array(
				'testMarker'  => '/* testPublicSetProperty */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'public(set)',
			),
			'property, public set, no read visibility, uppercase'      => array(
				'testMarker'  => '/* testPublicSetPropertyUC */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'PUBLIC(SET)',
			),
			'property, public set, has read visibility, lowercase'     => array(
				'testMarker'  => '/* testPublicPublicSetProperty */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'public(set)',
			),
			'property, public set, has read visibility, uppercase'     => array(
				'testMarker'  => '/* testPublicPublicSetPropertyUC */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'PUBLIC(SET)',
			),
			'property, protected set, no read visibility, lowercase'   => array(
				'testMarker'  => '/* testProtectedSetProperty */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'protected(set)',
			),
			'property, protected set, no read visibility, uppercase'   => array(
				'testMarker'  => '/* testProtectedSetPropertyUC */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'PROTECTED(SET)',
			),
			'property, protected set, has read visibility, lowercase'  => array(
				'testMarker'  => '/* testPublicProtectedSetProperty */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'protected(set)',
			),
			'property, protected set, has read visibility, uppercase'  => array(
				'testMarker'  => '/* testPublicProtectedSetPropertyUC */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'PROTECTED(SET)',
			),
			'property, private set, no read visibility, lowercase'     => array(
				'testMarker'  => '/* testPrivateSetProperty */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'private(set)',
			),
			'property, private set, no read visibility, uppercase'     => array(
				'testMarker'  => '/* testPrivateSetPropertyUC */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'PRIVATE(SET)',
			),
			'property, private set, has read visibility, lowercase'    => array(
				'testMarker'  => '/* testPublicPrivateSetProperty */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'private(set)',
			),
			'property, private set, has read visibility, uppercase'    => array(
				'testMarker'  => '/* testPublicPrivateSetPropertyUC */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'PRIVATE(SET)',
			),

			// Constructor property promotion.
			'promotion, public set, no read visibility, lowercase'     => array(
				'testMarker'  => '/* testPublicSetCPP */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'public(set)',
			),
			'promotion, public set, no read visibility, uppercase'     => array(
				'testMarker'  => '/* testPublicSetCPPUC */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'PUBLIC(SET)',
			),
			'promotion, public set, has read visibility, lowercase'    => array(
				'testMarker'  => '/* testPublicPublicSetCPP */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'public(set)',
			),
			'promotion, public set, has read visibility, uppercase'    => array(
				'testMarker'  => '/* testPublicPublicSetCPPUC */',
				'testType'    => 'T_PUBLIC_SET',
				'testContent' => 'PUBLIC(SET)',
			),
			'promotion, protected set, no read visibility, lowercase'  => array(
				'testMarker'  => '/* testProtectedSetCPP */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'protected(set)',
			),
			'promotion, protected set, no read visibility, uppercase'  => array(
				'testMarker'  => '/* testProtectedSetCPPUC */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'PROTECTED(SET)',
			),
			'promotion, protected set, has read visibility, lowercase' => array(
				'testMarker'  => '/* testPublicProtectedSetCPP */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'protected(set)',
			),
			'promotion, protected set, has read visibility, uppercase' => array(
				'testMarker'  => '/* testPublicProtectedSetCPPUC */',
				'testType'    => 'T_PROTECTED_SET',
				'testContent' => 'PROTECTED(SET)',
			),
			'promotion, private set, no read visibility, lowercase'    => array(
				'testMarker'  => '/* testPrivateSetCPP */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'private(set)',
			),
			'promotion, private set, no read visibility, uppercase'    => array(
				'testMarker'  => '/* testPrivateSetCPPUC */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'PRIVATE(SET)',
			),
			'promotion, private set, has read visibility, lowercase'   => array(
				'testMarker'  => '/* testPublicPrivateSetCPP */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'private(set)',
			),
			'promotion, private set, has read visibility, uppercase'   => array(
				'testMarker'  => '/* testPublicPrivateSetCPPUC */',
				'testType'    => 'T_PRIVATE_SET',
				'testContent' => 'PRIVATE(SET)',
			),
		);
	}//end dataAsymmetricVisibility()


	/**
	 * Test that things that are not asymmetric visibility keywords are not
	 * tokenized as such.
	 *
	 * @param string $testMarker  The comment which prefaces the target token in the test file.
	 * @param string $testType    The expected token type.
	 * @param string $testContent The token content to look for.
	 *
	 * @dataProvider dataNotAsymmetricVisibility
	 *
	 * @return void
	 */
	public function testNotAsymmetricVisibility( $testMarker, $testType, $testContent ) {
		$tokens     = $this->phpcsFile->getTokens();
		$target     = $this->getTargetToken(
			$testMarker,
			array( constant( $testType ) ),
			$testContent
		);
		$tokenArray = $tokens[ $target ];

		$this->assertSame(
			$testType,
			$tokenArray['type'],
			'Token tokenized as ' . $tokenArray['type'] . ' (type)'
		);
		$this->assertSame(
			constant( $testType ),
			$tokenArray['code'],
			'Token tokenized as ' . $tokenArray['type'] . ' (code)'
		);
	}//end testNotAsymmetricVisibility()


	/**
	 * Data provider.
	 *
	 * @see testNotAsymmetricVisibility()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataNotAsymmetricVisibility() {
		return array(
			'property, invalid case 1'   => array(
				'testMarker'  => '/* testInvalidUnsetProperty */',
				'testType'    => 'T_PUBLIC',
				'testContent' => 'public',
			),
			'property, invalid case 2'   => array(
				'testMarker'  => '/* testInvalidSpaceProperty */',
				'testType'    => 'T_PUBLIC',
				'testContent' => 'public',
			),
			'property, invalid case 3'   => array(
				'testMarker'  => '/* testInvalidCommentProperty */',
				'testType'    => 'T_PROTECTED',
				'testContent' => 'protected',
			),
			'property, invalid case 4'   => array(
				'testMarker'  => '/* testInvalidGetProperty */',
				'testType'    => 'T_PRIVATE',
				'testContent' => 'private',
			),
			'property, invalid case 5'   => array(
				'testMarker'  => '/* testInvalidNoParenProperty */',
				'testType'    => 'T_PRIVATE',
				'testContent' => 'private',
			),

			// Constructor property promotion.
			'promotion, invalid case 1'  => array(
				'testMarker'  => '/* testInvalidUnsetCPP */',
				'testType'    => 'T_PUBLIC',
				'testContent' => 'public',
			),
			'promotion, invalid case 2'  => array(
				'testMarker'  => '/* testInvalidSpaceCPP */',
				'testType'    => 'T_PUBLIC',
				'testContent' => 'public',
			),
			'promotion, invalid case 3'  => array(
				'testMarker'  => '/* testInvalidCommentCPP */',
				'testType'    => 'T_PROTECTED',
				'testContent' => 'protected',
			),
			'promotion, invalid case 4'  => array(
				'testMarker'  => '/* testInvalidGetCPP */',
				'testType'    => 'T_PRIVATE',
				'testContent' => 'private',
			),
			'promotion, invalid case 5'  => array(
				'testMarker'  => '/* testInvalidNoParenCPP */',
				'testType'    => 'T_PRIVATE',
				'testContent' => 'private',
			),

			// Context sensitivitiy.
			'protected as function name' => array(
				'testMarker'  => '/* testProtectedFunctionName */',
				'testType'    => 'T_STRING',
				'testContent' => 'protected',
			),
			'public as function name'    => array(
				'testMarker'  => '/* testPublicFunctionName */',
				'testType'    => 'T_STRING',
				'testContent' => 'public',
			),
			'set as parameter type'      => array(
				'testMarker'  => '/* testSetParameterType */',
				'testType'    => 'T_STRING',
				'testContent' => 'Set',
			),

			// Live coding.
			'live coding'                => array(
				'testMarker'  => '/* testLiveCoding */',
				'testType'    => 'T_PRIVATE',
				'testContent' => 'private',
			),
		);
	}//end dataNotAsymmetricVisibility()
}//end class
