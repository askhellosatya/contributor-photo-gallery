<?php
/**
 * Tests for the \PHP_CodeSniffer\Filters\GitModified class.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2023 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Filters;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Filters\GitModified;
use PHP_CodeSniffer\Tests\Core\Filters\AbstractFilterTestCase;
use RecursiveArrayIterator;
use ReflectionMethod;

/**
 * Tests for the \PHP_CodeSniffer\Filters\GitModified class.
 *
 * @covers \PHP_CodeSniffer\Filters\GitModified
 */
final class GitModifiedTest extends AbstractFilterTestCase {



	/**
	 * Test filtering a file list for excluded paths.
	 *
	 * @return void
	 */
	public function testFileNamePassesAsBasePathWillTranslateToDirname() {
		$rootFile = self::getBaseDir() . '/autoload.php';

		$fakeDI          = new RecursiveArrayIterator( self::getFakeFileList() );
		$constructorArgs = array(
			$fakeDI,
			$rootFile,
			self::$config,
			self::$ruleset,
		);
		$mockObj         = $this->getMockedClass( 'PHP_CodeSniffer\Filters\GitModified', $constructorArgs, array( 'exec' ) );

		$mockObj->expects( $this->once() )
			->method( 'exec' )
			->willReturn( array( 'autoload.php' ) );

		$this->assertSame( array( $rootFile ), $this->getFilteredResultsAsArray( $mockObj ) );
	}//end testFileNamePassesAsBasePathWillTranslateToDirname()


	/**
	 * Test filtering a file list for excluded paths.
	 *
	 * @param array<string> $inputPaths        List of file paths to be filtered.
	 * @param array<string> $outputGitModified Simulated "git modified" output.
	 * @param array<string> $expectedOutput    Expected filtering result.
	 *
	 * @dataProvider dataAcceptOnlyGitModified
	 *
	 * @return void
	 */
	public function testAcceptOnlyGitModified( $inputPaths, $outputGitModified, $expectedOutput ) {
		$fakeDI          = new RecursiveArrayIterator( $inputPaths );
		$constructorArgs = array(
			$fakeDI,
			self::getBaseDir(),
			self::$config,
			self::$ruleset,
		);
		$mockObj         = $this->getMockedClass( 'PHP_CodeSniffer\Filters\GitModified', $constructorArgs, array( 'exec' ) );

		$mockObj->expects( $this->once() )
			->method( 'exec' )
			->willReturn( $outputGitModified );

		$this->assertSame( $expectedOutput, $this->getFilteredResultsAsArray( $mockObj ) );
	}//end testAcceptOnlyGitModified()


	/**
	 * Data provider.
	 *
	 * @see testAcceptOnlyGitModified
	 *
	 * @return array<string, array<string, array<string>>>
	 */
	public static function dataAcceptOnlyGitModified() {
		$basedir      = self::getBaseDir();
		$fakeFileList = self::getFakeFileList();

		$testCases = array(
			'no files marked as git modified' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(),
				'expectedOutput'    => array(),
			),

			'files marked as git modified which don\'t actually exist' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'src/WillNotExist.php',
					'src/WillNotExist.bak',
					'src/WillNotExist.orig',
				),
				'expectedOutput'    => array(),
			),

			'single file marked as git modified - file in root dir' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'autoload.php',
				),
				'expectedOutput'    => array(
					$basedir . '/autoload.php',
				),
			),
			'single file marked as git modified - file in sub dir' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'src/Standards/Generic/Sniffs/Classes/DuplicateClassNameSniff.php',
				),
				'expectedOutput'    => array(
					$basedir . '/src',
					$basedir . '/src/Standards',
					$basedir . '/src/Standards/Generic',
					$basedir . '/src/Standards/Generic/Sniffs',
					$basedir . '/src/Standards/Generic/Sniffs/Classes',
					$basedir . '/src/Standards/Generic/Sniffs/Classes/DuplicateClassNameSniff.php',
				),
			),

			'multiple files marked as git modified, none valid for scan' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'.gitignore',
					'phpcs.xml.dist',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.js.fixed',
				),
				'expectedOutput'    => array(
					$basedir . '/src',
					$basedir . '/src/Standards',
					$basedir . '/src/Standards/Squiz',
					$basedir . '/src/Standards/Squiz/Tests',
					$basedir . '/src/Standards/Squiz/Tests/WhiteSpace',
				),
			),

			'multiple files marked as git modified, only one file valid for scan' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'.gitignore',
					'src/Standards/Generic/Docs/Classes/DuplicateClassNameStandard.xml',
					'src/Standards/Generic/Sniffs/Classes/DuplicateClassNameSniff.php',
				),
				'expectedOutput'    => array(
					$basedir . '/src',
					$basedir . '/src/Standards',
					$basedir . '/src/Standards/Generic',
					$basedir . '/src/Standards/Generic/Docs',
					$basedir . '/src/Standards/Generic/Docs/Classes',
					$basedir . '/src/Standards/Generic/Sniffs',
					$basedir . '/src/Standards/Generic/Sniffs/Classes',
					$basedir . '/src/Standards/Generic/Sniffs/Classes/DuplicateClassNameSniff.php',
				),
			),

			'multiple files marked as git modified, multiple files valid for scan' => array(
				'inputPaths'        => $fakeFileList,
				'outputGitModified' => array(
					'.yamllint.yml',
					'autoload.php',
					'src/Standards/Squiz/Sniffs/WhiteSpace/OperatorSpacingSniff.php',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.1.inc',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.1.inc.fixed',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.js',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.js.fixed',
					'src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.php',
				),
				'expectedOutput'    => array(
					$basedir . '/autoload.php',
					$basedir . '/src',
					$basedir . '/src/Standards',
					$basedir . '/src/Standards/Squiz',
					$basedir . '/src/Standards/Squiz/Sniffs',
					$basedir . '/src/Standards/Squiz/Sniffs/WhiteSpace',
					$basedir . '/src/Standards/Squiz/Sniffs/WhiteSpace/OperatorSpacingSniff.php',
					$basedir . '/src/Standards/Squiz/Tests',
					$basedir . '/src/Standards/Squiz/Tests/WhiteSpace',
					$basedir . '/src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.1.inc',
					$basedir . '/src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.js',
					$basedir . '/src/Standards/Squiz/Tests/WhiteSpace/OperatorSpacingUnitTest.php',
				),
			),
		);

		return $testCases;
	}//end dataAcceptOnlyGitModified()


	/**
	 * Test filtering a file list for excluded paths.
	 *
	 * @param string        $cmd      Command to run.
	 * @param array<string> $expected Expected return value.
	 *
	 * @dataProvider dataExecAlwaysReturnsArray
	 *
	 * @return void
	 */
	public function testExecAlwaysReturnsArray( $cmd, $expected ) {
		if ( is_dir( __DIR__ . '/../../../.git' ) === false ) {
			$this->markTestSkipped( 'Not a git repository' );
		}

		if ( Config::getExecutablePath( 'git' ) === null ) {
			$this->markTestSkipped( 'git command not available' );
		}

		$fakeDI = new RecursiveArrayIterator( self::getFakeFileList() );
		$filter = new GitModified( $fakeDI, '/', self::$config, self::$ruleset );

		$reflMethod = new ReflectionMethod( $filter, 'exec' );
		( PHP_VERSION_ID < 80100 ) && $reflMethod->setAccessible( true );
		$result = $reflMethod->invoke( $filter, $cmd );

		$this->assertSame( $expected, $result );
	}//end testExecAlwaysReturnsArray()


	/**
	 * Data provider.
	 *
	 * @see testExecAlwaysReturnsArray
	 *
	 * {@internal Missing: test with a command which yields a `false` return value.
	 *            JRF: I've not managed to find a command which does so, let alone one, which then
	 *            doesn't have side-effects of uncatchable output while running the tests.}
	 *
	 * @return array<string, array<string, string|array<string>>>
	 */
	public static function dataExecAlwaysReturnsArray() {
		return array(
			'valid command which won\'t have any output unless files in the bin dir have been modified' => array(
				// Largely using the command used in the filter, but only checking the bin dir.
				// This should prevent the test unexpectedly failing during local development (in most cases).
				'cmd'      => 'git ls-files -o -m --exclude-standard -- ' . escapeshellarg( self::getBaseDir() . '/bin' ),
				'expected' => array(),
			),
			'valid command which will have output' => array(
				'cmd'      => 'git ls-files --exclude-standard -- ' . escapeshellarg( self::getBaseDir() . '/bin' ),
				'expected' => array(
					'bin/phpcbf',
					'bin/phpcbf.bat',
					'bin/phpcs',
					'bin/phpcs.bat',
				),
			),
		);
	}//end dataExecAlwaysReturnsArray()
}//end class
