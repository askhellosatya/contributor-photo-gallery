<?php
/**
 * Tests for the \PHP_CodeSniffer\Config --sniffs and --exclude arguments.
 *
 * @author  Dan Wallis <dan@wallis.nz>
 * @license https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Config;

use PHP_CodeSniffer\Tests\ConfigDouble;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the \PHP_CodeSniffer\Config --sniffs and --exclude arguments.
 *
 * @covers \PHP_CodeSniffer\Config::parseSniffCodes
 * @covers \PHP_CodeSniffer\Config::processLongArgument
 */
final class SniffsExcludeArgsTest extends TestCase {



	/**
	 * Ensure that the expected error message is returned for invalid arguments.
	 *
	 * @param string        $argument   Either 'sniffs' or 'exclude'.
	 * @param string        $value      List of sniffs to include / exclude.
	 * @param array<string> $errors     Sniff code and associated help text.
	 * @param string|null   $suggestion Help text shown to end user with correct syntax for argument.
	 *
	 * @return       void
	 * @dataProvider dataInvalid
	 */
	public function testInvalid( $argument, $value, $errors, $suggestion ) {
		$cmd = 'phpcs';
		if ( PHP_CODESNIFFER_CBF === true ) {
			$cmd = 'phpcbf';
		}

		$exception = 'PHP_CodeSniffer\Exceptions\DeepExitException';
		$message   = 'ERROR: The --' . $argument . ' option only supports sniff codes.' . PHP_EOL;
		$message  .= 'Sniff codes are in the form "Standard.Category.Sniff".' . PHP_EOL;
		$message  .= PHP_EOL;
		$message  .= 'The following problems were detected:' . PHP_EOL;
		$message  .= '* ' . implode( PHP_EOL . '* ', $errors ) . PHP_EOL;

		if ( $suggestion !== null ) {
			$message .= PHP_EOL;
			$message .= "Perhaps try --$argument=\"$suggestion\" instead." . PHP_EOL;
		}

		$message .= PHP_EOL;
		$message .= "Run \"{$cmd} --help\" for usage information" . PHP_EOL;
		$message .= PHP_EOL;

		if ( method_exists( $this, 'expectException' ) === true ) {
			// PHPUnit 5+.
			$this->expectException( $exception );
			$this->expectExceptionMessage( $message );
		} else {
			// PHPUnit 4.
			$this->setExpectedException( $exception, $message );
		}

		new ConfigDouble( array( "--$argument=$value" ) );
	}//end testInvalid()


	/**
	 * Data provider for testInvalid().
	 *
	 * @see    self::testInvalid()
	 * @return array<string, array<string, array<string>|string|null>>
	 */
	public static function dataInvalid() {
		$arguments = array(
			'sniffs',
			'exclude',
		);
		$data      = array();

		foreach ( $arguments as $argument ) {
			// Empty values are errors.
			$data[ $argument . '; empty string' ]     = array(
				'argument'   => $argument,
				'value'      => '',
				'errors'     => array(
					'No codes specified / empty argument',
				),
				'suggestion' => null,
			);
			$data[ $argument . '; one comma alone' ]  = array(
				'argument'   => $argument,
				'value'      => ',',
				'errors'     => array(
					'No codes specified / empty argument',
				),
				'suggestion' => null,
			);
			$data[ $argument . '; two commas alone' ] = array(
				'argument'   => $argument,
				'value'      => ',,',
				'errors'     => array(
					'No codes specified / empty argument',
				),
				'suggestion' => null,
			);

			// A standard is not a valid sniff.
			$data[ $argument . '; standard' ] = array(
				'argument'   => $argument,
				'value'      => 'Standard',
				'errors'     => array(
					'Standard codes are not supported: Standard',
				),
				'suggestion' => null,
			);

			// A category is not a valid sniff.
			$data[ $argument . '; category' ] = array(
				'argument'   => $argument,
				'value'      => 'Standard.Category',
				'errors'     => array(
					'Category codes are not supported: Standard.Category',
				),
				'suggestion' => null,
			);

			// An error-code is not a valid sniff.
			$data[ $argument . '; error-code' ] = array(
				'argument'   => $argument,
				'value'      => 'Standard.Category.Sniff.Code',
				'errors'     => array(
					'Message codes are not supported: Standard.Category.Sniff.Code',
				),
				'suggestion' => 'Standard.Category.Sniff',
			);

			// Too many dots.
			$data[ $argument . '; too many dots' ] = array(
				'argument'   => $argument,
				'value'      => 'Standard.Category.Sniff.Code.Extra',
				'errors'     => array(
					'Too many parts: Standard.Category.Sniff.Code.Extra',
				),
				'suggestion' => 'Standard.Category.Sniff',
			);

			// All errors are reported in one go.
			$data[ $argument . '; two errors' ] = array(
				'argument'   => $argument,
				'value'      => 'StandardOne,StandardTwo',
				'errors'     => array(
					'Standard codes are not supported: StandardOne',
					'Standard codes are not supported: StandardTwo',
				),
				'suggestion' => null,
			);

			// Order of valid/invalid does not impact error reporting.
			$data[ $argument . '; valid followed by invalid' ] = array(
				'argument'   => $argument,
				'value'      => 'StandardOne.Category.Sniff,StandardTwo.Category',
				'errors'     => array(
					'Category codes are not supported: StandardTwo.Category',
				),
				'suggestion' => 'StandardOne.Category.Sniff',
			);
			$data[ $argument . '; invalid followed by valid' ] = array(
				'argument'   => $argument,
				'value'      => 'StandardOne.Category,StandardTwo.Category.Sniff',
				'errors'     => array(
					'Category codes are not supported: StandardOne.Category',
				),
				'suggestion' => 'StandardTwo.Category.Sniff',
			);

			// Different cases are reported individually (in duplicate), but suggestions are reduced.
			$data[ $argument . '; case mismatch - different errors' ] = array(
				'argument'   => $argument,
				'value'      => 'Standard.Category.Sniff.Code,sTANDARD.cATEGORY.sNIFF.cODE.eXTRA',
				'errors'     => array(
					'Message codes are not supported: Standard.Category.Sniff.Code',
					'Too many parts: sTANDARD.cATEGORY.sNIFF.cODE.eXTRA',
				),
				'suggestion' => 'Standard.Category.Sniff',
			);
			$data[ $argument . '; case mismatch - same error' ]       = array(
				'argument'   => $argument,
				'value'      => 'sTANDARD.cATEGORY.sNIFF.cODE,Standard.Category.Sniff.Code',
				'errors'     => array(
					'Message codes are not supported: sTANDARD.cATEGORY.sNIFF.cODE',
					'Message codes are not supported: Standard.Category.Sniff.Code',
				),
				'suggestion' => 'sTANDARD.cATEGORY.sNIFF',
			);
		}//end foreach

		return $data;
	}//end dataInvalid()


	/**
	 * Ensure that the valid data does not throw an exception, and the value is stored.
	 *
	 * @param string        $argument Either 'sniffs' or 'exclude'.
	 * @param string        $value    List of sniffs to include or exclude.
	 * @param array<string> $result   Expected sniffs to be set on the Config object.
	 *
	 * @return       void
	 * @dataProvider dataValid
	 */
	public function testValid( $argument, $value, $result ) {
		$config = new ConfigDouble( array( "--$argument=$value" ) );

		$this->assertSame( $result, $config->$argument );
	}//end testValid()


	/**
	 * Data provider for testValid().
	 *
	 * @see    self::testValid()
	 * @return array<string, array<string, array<string>|string>>
	 */
	public static function dataValid() {
		$arguments = array(
			'sniffs',
			'exclude',
		);
		$data      = array();

		foreach ( $arguments as $argument ) {
			$data[ $argument . '; one valid sniff' ]  = array(
				'argument' => $argument,
				'value'    => 'Standard.Category.Sniff',
				'result'   => array( 'Standard.Category.Sniff' ),
			);
			$data[ $argument . '; two valid sniffs' ] = array(
				'argument' => $argument,
				'value'    => 'StandardOne.Category.Sniff,StandardTwo.Category.Sniff',
				'result'   => array(
					'StandardOne.Category.Sniff',
					'StandardTwo.Category.Sniff',
				),
			);

			// Rogue commas are quietly ignored.
			$data[ $argument . '; trailing comma' ]              = array(
				'argument' => $argument,
				'value'    => 'Standard.Category.Sniff,',
				'result'   => array( 'Standard.Category.Sniff' ),
			);
			$data[ $argument . '; double comma between sniffs' ] = array(
				'argument' => $argument,
				'value'    => 'StandardOne.Category.Sniff,,StandardTwo.Category.Sniff',
				'result'   => array(
					'StandardOne.Category.Sniff',
					'StandardTwo.Category.Sniff',
				),
			);

			// Duplicates are reduced silently.
			$data[ $argument . '; one valid sniff twice' ]              = array(
				'argument' => $argument,
				'value'    => 'Standard.Category.Sniff,Standard.Category.Sniff',
				'result'   => array( 'Standard.Category.Sniff' ),
			);
			$data[ $argument . '; one valid sniff in different cases' ] = array(
				'argument' => $argument,
				'value'    => 'Standard.Category.Sniff, standard.category.sniff, STANDARD.CATEGORY.SNIFF',
				'result'   => array( 'Standard.Category.Sniff' ),
			);
		}//end foreach

		return $data;
	}//end dataValid()


	/**
	 * Ensure that only the first argument is processed and others are ignored.
	 *
	 * @param string $argument Either 'sniffs' or 'exclude'.
	 *
	 * @return       void
	 * @dataProvider dataOnlySetOnce
	 */
	public function testOnlySetOnce( $argument ) {
		$config = new ConfigDouble(
			array(
				"--$argument=StandardOne.Category.Sniff",
				"--$argument=StandardTwo.Category.Sniff",
				"--$argument=Standard.AnotherCategory.Sniff",
			)
		);

		$this->assertSame( array( 'StandardOne.Category.Sniff' ), $config->$argument );
	}//end testOnlySetOnce()


	/**
	 * Data provider for testOnlySetOnce().
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataOnlySetOnce() {
		return array(
			'sniffs'  => array( 'sniffs' ),
			'exclude' => array( 'exclude' ),
		);
	}//end dataOnlySetOnce()
}//end class
