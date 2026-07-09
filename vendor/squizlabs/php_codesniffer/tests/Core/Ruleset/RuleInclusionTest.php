<?php
/**
 * Tests for the \PHP_CodeSniffer\Ruleset class.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2019 Juliette Reinders Folmer. All rights reserved.
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Ruleset;

use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Tests\ConfigDouble;
use PHP_CodeSniffer\Tests\Core\Ruleset\AbstractRulesetTestCase;

/**
 * Tests for the \PHP_CodeSniffer\Ruleset class.
 *
 * @covers \PHP_CodeSniffer\Ruleset
 */
final class RuleInclusionTest extends AbstractRulesetTestCase {


	/**
	 * The Ruleset object.
	 *
	 * @var \PHP_CodeSniffer\Ruleset
	 */
	protected static $ruleset;

	/**
	 * Path to the ruleset file.
	 *
	 * @var string
	 */
	private static $standard = '';

	/**
	 * The original content of the ruleset.
	 *
	 * @var string
	 */
	private static $contents = '';


	/**
	 * Initialize the config and ruleset objects based on the `RuleInclusionTest.xml` ruleset file.
	 *
	 * @before
	 *
	 * @return void
	 */
	protected function initializeConfigAndRuleset() {
		if ( self::$standard === '' ) {
			$standard       = __DIR__ . '/' . basename( __FILE__, '.php' ) . '.xml';
			self::$standard = $standard;

			// On-the-fly adjust the ruleset test file to be able to test
			// sniffs included with relative paths.
			$contents       = file_get_contents( $standard );
			self::$contents = $contents;

			$repoRootDir = basename( dirname( dirname( dirname( __DIR__ ) ) ) );

			$newPath = $repoRootDir;
			if ( DIRECTORY_SEPARATOR === '\\' ) {
				$newPath = str_replace( '\\', '/', $repoRootDir );
			}

			$adjusted = str_replace( '%path_root_dir%', $newPath, $contents );

			if ( file_put_contents( $standard, $adjusted ) === false ) {
				self::markTestSkipped( 'On the fly ruleset adjustment failed' );
			}

			$config        = new ConfigDouble( array( "--standard=$standard" ) );
			self::$ruleset = new Ruleset( $config );
		}//end if
	}//end initializeConfigAndRuleset()


	/**
	 * Reset ruleset file.
	 *
	 * @after
	 *
	 * @return void
	 */
	public function resetRuleset() {
		file_put_contents( self::$standard, self::$contents );
	}//end resetRuleset()


	/**
	 * Test that sniffs are registered.
	 *
	 * @return void
	 */
	public function testHasSniffCodes() {
		$this->assertCount( 49, self::$ruleset->sniffCodes );
	}//end testHasSniffCodes()


	/**
	 * Test that sniffs are correctly registered, independently of the syntax used to include the sniff.
	 *
	 * @param string $key   Expected array key.
	 * @param string $value Expected array value.
	 *
	 * @dataProvider dataRegisteredSniffCodes
	 *
	 * @return void
	 */
	public function testRegisteredSniffCodes( $key, $value ) {
		$this->assertArrayHasKey( $key, self::$ruleset->sniffCodes );
		$this->assertSame( $value, self::$ruleset->sniffCodes[ $key ] );
	}//end testRegisteredSniffCodes()


	/**
	 * Data provider.
	 *
	 * @see self::testRegisteredSniffCodes()
	 *
	 * @return array<array<string>>
	 */
	public static function dataRegisteredSniffCodes() {
		return array(
			array(
				'PSR2.Classes.ClassDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff',
			),
			array(
				'PSR2.Classes.PropertyDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\PropertyDeclarationSniff',
			),
			array(
				'PSR2.ControlStructures.ControlStructureSpacing',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff',
			),
			array(
				'PSR2.ControlStructures.ElseIfDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ElseIfDeclarationSniff',
			),
			array(
				'PSR2.ControlStructures.SwitchDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\SwitchDeclarationSniff',
			),
			array(
				'PSR2.Files.ClosingTag',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\ClosingTagSniff',
			),
			array(
				'PSR2.Files.EndFileNewline',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\EndFileNewlineSniff',
			),
			array(
				'PSR2.Methods.FunctionCallSignature',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff',
			),
			array(
				'PSR2.Methods.FunctionClosingBrace',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionClosingBraceSniff',
			),
			array(
				'PSR2.Methods.MethodDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\MethodDeclarationSniff',
			),
			array(
				'PSR2.Namespaces.NamespaceDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\NamespaceDeclarationSniff',
			),
			array(
				'PSR2.Namespaces.UseDeclaration',
				'PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\UseDeclarationSniff',
			),
			array(
				'PSR1.Classes.ClassDeclaration',
				'PHP_CodeSniffer\Standards\PSR1\Sniffs\Classes\ClassDeclarationSniff',
			),
			array(
				'PSR1.Files.SideEffects',
				'PHP_CodeSniffer\Standards\PSR1\Sniffs\Files\SideEffectsSniff',
			),
			array(
				'PSR1.Methods.CamelCapsMethodName',
				'PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff',
			),
			array(
				'Generic.PHP.DisallowAlternativePHPTags',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\DisallowAlternativePHPTagsSniff',
			),
			array(
				'Generic.PHP.DisallowShortOpenTag',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\DisallowShortOpenTagSniff',
			),
			array(
				'Generic.Files.ByteOrderMark',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\ByteOrderMarkSniff',
			),
			array(
				'Squiz.Classes.ValidClassName',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff',
			),
			array(
				'Generic.NamingConventions.UpperCaseConstantName',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\UpperCaseConstantNameSniff',
			),
			array(
				'Generic.Files.LineEndings',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineEndingsSniff',
			),
			array(
				'Generic.Files.LineLength',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff',
			),
			array(
				'Squiz.WhiteSpace.SuperfluousWhitespace',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff',
			),
			array(
				'Generic.Formatting.DisallowMultipleStatements',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\DisallowMultipleStatementsSniff',
			),
			array(
				'Generic.WhiteSpace.ScopeIndent',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ScopeIndentSniff',
			),
			array(
				'Generic.WhiteSpace.DisallowTabIndent',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff',
			),
			array(
				'Generic.PHP.LowerCaseKeyword',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseKeywordSniff',
			),
			array(
				'Generic.PHP.LowerCaseConstant',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseConstantSniff',
			),
			array(
				'Squiz.Scope.MethodScope',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Scope\MethodScopeSniff',
			),
			array(
				'Squiz.WhiteSpace.ScopeKeywordSpacing',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeKeywordSpacingSniff',
			),
			array(
				'Squiz.Functions.FunctionDeclaration',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationSniff',
			),
			array(
				'Squiz.Functions.LowercaseFunctionKeywords',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\LowercaseFunctionKeywordsSniff',
			),
			array(
				'Squiz.Functions.FunctionDeclarationArgumentSpacing',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationArgumentSpacingSniff',
			),
			array(
				'PEAR.Functions.ValidDefaultValue',
				'PHP_CodeSniffer\Standards\PEAR\Sniffs\Functions\ValidDefaultValueSniff',
			),
			array(
				'Squiz.Functions.MultiLineFunctionDeclaration',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff',
			),
			array(
				'Generic.Functions.FunctionCallArgumentSpacing',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Functions\FunctionCallArgumentSpacingSniff',
			),
			array(
				'Squiz.ControlStructures.ControlSignature',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ControlSignatureSniff',
			),
			array(
				'Squiz.WhiteSpace.ControlStructureSpacing',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ControlStructureSpacingSniff',
			),
			array(
				'Squiz.WhiteSpace.ScopeClosingBrace',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeClosingBraceSniff',
			),
			array(
				'Squiz.ControlStructures.ForEachLoopDeclaration',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForEachLoopDeclarationSniff',
			),
			array(
				'Squiz.ControlStructures.ForLoopDeclaration',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForLoopDeclarationSniff',
			),
			array(
				'Squiz.ControlStructures.LowercaseDeclaration',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\LowercaseDeclarationSniff',
			),
			array(
				'Generic.ControlStructures.InlineControlStructure',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\ControlStructures\InlineControlStructureSniff',
			),
			array(
				'PSR12.Operators.OperatorSpacing',
				'PHP_CodeSniffer\Standards\PSR12\Sniffs\Operators\OperatorSpacingSniff',
			),
			array(
				'Generic.Arrays.ArrayIndent',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\ArrayIndentSniff',
			),
			array(
				'Generic.Metrics.CyclomaticComplexity',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\CyclomaticComplexitySniff',
			),
			array(
				'Squiz.Files.FileExtension',
				'PHP_CodeSniffer\Standards\Squiz\Sniffs\Files\FileExtensionSniff',
			),
			array(
				'Generic.NamingConventions.CamelCapsFunctionName',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\CamelCapsFunctionNameSniff',
			),
			array(
				'Generic.Metrics.NestingLevel',
				'PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\NestingLevelSniff',
			),
		);
	}//end dataRegisteredSniffCodes()


	/**
	 * Test that setting properties for standards, categories, sniffs works for all supported rule
	 * inclusion methods.
	 *
	 * @param string          $sniffClass    The name of the sniff class.
	 * @param string          $propertyName  The name of the changed property.
	 * @param string|int|bool $expectedValue The value expected for the property.
	 *
	 * @dataProvider dataSettingProperties
	 *
	 * @return void
	 */
	public function testSettingProperties( $sniffClass, $propertyName, $expectedValue ) {
		$this->assertArrayHasKey( $sniffClass, self::$ruleset->sniffs );
		$this->assertXObjectHasProperty( $propertyName, self::$ruleset->sniffs[ $sniffClass ] );

		$actualValue = self::$ruleset->sniffs[ $sniffClass ]->$propertyName;
		$this->assertSame( $expectedValue, $actualValue );
	}//end testSettingProperties()


	/**
	 * Data provider.
	 *
	 * @see self::testSettingProperties()
	 *
	 * @return array<string, array<string, string|int|bool>>
	 */
	public static function dataSettingProperties() {
		return array(
			'Set property for complete standard: PSR2 ClassDeclaration'                                  => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff',
				'propertyName'  => 'indent',
				'expectedValue' => '20',
			),
			'Set property for complete standard: PSR2 SwitchDeclaration'                                 => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\SwitchDeclarationSniff',
				'propertyName'  => 'indent',
				'expectedValue' => '20',
			),
			'Set property for complete standard: PSR2 FunctionCallSignature'                             => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff',
				'propertyName'  => 'indent',
				'expectedValue' => '20',
			),
			'Set property for complete category: PSR12 OperatorSpacing'                                  => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\PSR12\Sniffs\Operators\OperatorSpacingSniff',
				'propertyName'  => 'ignoreSpacingBeforeAssignments',
				'expectedValue' => false,
			),
			'Set property for individual sniff: Generic ArrayIndent'                                     => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\ArrayIndentSniff',
				'propertyName'  => 'indent',
				'expectedValue' => '2',
			),
			'Set property for individual sniff using sniff file inclusion: Generic LineLength'           => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff',
				'propertyName'  => 'lineLimit',
				'expectedValue' => '10',
			),
			'Set property for individual sniff using sniff file inclusion: CamelCapsFunctionName'        => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\CamelCapsFunctionNameSniff',
				'propertyName'  => 'strict',
				'expectedValue' => false,
			),
			'Set property for individual sniff via included ruleset: NestingLevel - nestingLevel'        => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\NestingLevelSniff',
				'propertyName'  => 'nestingLevel',
				'expectedValue' => '2',
			),
			'Set property for all sniffs in an included ruleset: NestingLevel - absoluteNestingLevel'    => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\NestingLevelSniff',
				'propertyName'  => 'absoluteNestingLevel',
				'expectedValue' => true,
			),

			// Testing that setting a property at error code level does *not* work.
			'Set property for error code will not change the sniff property value: CyclomaticComplexity' => array(
				'sniffClass'    => 'PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\CyclomaticComplexitySniff',
				'propertyName'  => 'complexity',
				'expectedValue' => 10,
			),
		);
	}//end dataSettingProperties()


	/**
	 * Test that setting properties for standards, categories on sniffs which don't support the property will
	 * silently ignore the property and not set it.
	 *
	 * @param string $sniffClass   The name of the sniff class.
	 * @param string $propertyName The name of the property which should not be set.
	 *
	 * @dataProvider dataSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails
	 *
	 * @return void
	 */
	public function testSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails( $sniffClass, $propertyName ) {
		$this->assertArrayHasKey( $sniffClass, self::$ruleset->sniffs, 'Sniff class ' . $sniffClass . ' not listed in registered sniffs' );
		$this->assertXObjectNotHasProperty( $propertyName, self::$ruleset->sniffs[ $sniffClass ] );
	}//end testSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails()


	/**
	 * Data provider.
	 *
	 * @see self::testSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails()
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function dataSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails() {
		return array(
			'Set property for complete standard: PSR2 ClassDeclaration'      => array(
				'sniffClass'   => 'PHP_CodeSniffer\Standards\PSR1\Sniffs\Classes\ClassDeclarationSniff',
				'propertyName' => 'setforallsniffs',
			),
			'Set property for complete standard: PSR2 FunctionCallSignature' => array(
				'sniffClass'   => 'PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff',
				'propertyName' => 'setforallsniffs',
			),
			'Set property for complete category: PSR12 OperatorSpacing'      => array(
				'sniffClass'   => 'PHP_CodeSniffer\Standards\PSR12\Sniffs\Operators\OperatorSpacingSniff',
				'propertyName' => 'setforallincategory',
			),
			'Set property for all sniffs in included category directory'     => array(
				'sniffClass'   => 'PHP_CodeSniffer\Standards\Squiz\Sniffs\Files\FileExtensionSniff',
				'propertyName' => 'setforsquizfilessniffs',
			),
		);
	}//end dataSettingInvalidPropertiesOnStandardsAndCategoriesSilentlyFails()
}//end class
