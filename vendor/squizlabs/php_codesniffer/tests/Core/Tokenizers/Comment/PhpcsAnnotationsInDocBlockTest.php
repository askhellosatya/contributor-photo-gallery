<?php
/**
 * Tests that PHPCS native annotations in docblocks are tokenized correctly.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\Comment;

/**
 * Tests that PHPCS native annotations in docblocks are tokenized correctly.
 *
 * @covers PHP_CodeSniffer\Tokenizers\Comment
 */
final class PhpcsAnnotationsInDocBlockTest extends CommentTestCase {



	/**
	 * Data provider.
	 *
	 * @see testDocblockOpenerCloser()
	 *
	 * @return array<string, array<string, string|int|array<int>>>
	 */
	public static function dataDocblockOpenerCloser() {
		return array(
			'Single-line: @phpcs:ignoreFile annotation'   => array(
				'marker'       => '/* testSingleLineDocIgnoreFileAnnotation */',
				'closerOffset' => 3,
				'expectedTags' => array(),
			),
			'Single-line: @phpcs:ignore annotation'       => array(
				'marker'       => '/* testSingleLineDocIgnoreAnnotation */',
				'closerOffset' => 3,
				'expectedTags' => array(),
			),
			'Single-line: @phpcs:disable annotation'      => array(
				'marker'       => '/* testSingleLineDocDisableAnnotation */',
				'closerOffset' => 3,
				'expectedTags' => array(),
			),
			'Single-line: @phpcs:enable annotation; no whitespace' => array(
				'marker'       => '/* testSingleLineDocEnableAnnotationNoWhitespace */',
				'closerOffset' => 2,
				'expectedTags' => array(),
			),

			'Multi-line: @phpcs:ignoreFile at the start'  => array(
				'marker'       => '/* testMultiLineDocIgnoreFileAnnotationAtStart */',
				'closerOffset' => 13,
				'expectedTags' => array(),
			),
			'Multi-line: @phpcs:ignore at the start'      => array(
				'marker'       => '/* testMultiLineDocIgnoreAnnotationAtStart */',
				'closerOffset' => 13,
				'expectedTags' => array( 10 ),
			),
			'Multi-line: @phpcs:disable at the start'     => array(
				'marker'       => '/* testMultiLineDocDisableAnnotationAtStart */',
				'closerOffset' => 13,
				'expectedTags' => array(),
			),
			'Multi-line: @phpcs:enable at the start'      => array(
				'marker'       => '/* testMultiLineDocEnableAnnotationAtStart */',
				'closerOffset' => 18,
				'expectedTags' => array( 13 ),
			),

			'Multi-line: @phpcs:ignoreFile in the middle' => array(
				'marker'       => '/* testMultiLineDocIgnoreFileAnnotationInMiddle */',
				'closerOffset' => 21,
				'expectedTags' => array(),
			),
			'Multi-line: @phpcs:ignore in the middle'     => array(
				'marker'       => '/* testMultiLineDocIgnoreAnnotationInMiddle */',
				'closerOffset' => 23,
				'expectedTags' => array( 5 ),
			),
			'Multi-line: @phpcs:disable in the middle'    => array(
				'marker'       => '/* testMultiLineDocDisableAnnotationInMiddle */',
				'closerOffset' => 26,
				'expectedTags' => array( 21 ),
			),
			'Multi-line: @phpcs:enable in the middle'     => array(
				'marker'       => '/* testMultiLineDocEnableAnnotationInMiddle */',
				'closerOffset' => 24,
				'expectedTags' => array( 21 ),
			),

			'Multi-line: @phpcs:ignoreFile at the end'    => array(
				'marker'       => '/* testMultiLineDocIgnoreFileAnnotationAtEnd */',
				'closerOffset' => 16,
				'expectedTags' => array( 5 ),
			),
			'Multi-line: @phpcs:ignore at the end'        => array(
				'marker'       => '/* testMultiLineDocIgnoreAnnotationAtEnd */',
				'closerOffset' => 16,
				'expectedTags' => array(),
			),
			'Multi-line: @phpcs:disable at the end'       => array(
				'marker'       => '/* testMultiLineDocDisableAnnotationAtEnd */',
				'closerOffset' => 18,
				'expectedTags' => array( 5 ),
			),
			'Multi-line: @phpcs:enable at the end'        => array(
				'marker'       => '/* testMultiLineDocEnableAnnotationAtEnd */',
				'closerOffset' => 16,
				'expectedTags' => array(),
			),
		);
	}//end dataDocblockOpenerCloser()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignoreFile annotation.
	 *
     * @phpcs:disable Squiz.Arrays.ArrayDeclaration.SpaceBeforeDoubleArrow -- Readability is better with alignment.
	 *
	 * @return void
	 */
	public function testSingleLineDocIgnoreFileAnnotation() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE_FILE => '@phpcs:ignoreFile ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocIgnoreFileAnnotation()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignore annotation.
	 *
	 * @return void
	 */
	public function testSingleLineDocIgnoreAnnotation() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE => '@phpcs:ignore Stnd.Cat.SniffName -- With reason ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocIgnoreAnnotation()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS disable annotation.
	 *
	 * @return void
	 */
	public function testSingleLineDocDisableAnnotation() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_DISABLE => '@phpcs:disable Stnd.Cat.SniffName,Stnd.Other ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocDisableAnnotation()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS enable annotation.
	 *
	 * @return void
	 */
	public function testSingleLineDocEnableAnnotationNoWhitespace() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_PHPCS_ENABLE => '@phpcs:enable Stnd.Cat.SniffName' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocEnableAnnotationNoWhitespace()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignoreFile annotation at the start.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreFileAnnotationAtStart() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE_FILE => '@phpcs:ignoreFile' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Something.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreFileAnnotationAtStart()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignore annotation at the start.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreAnnotationAtStart() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE => '@phpcs:ignore Stnd.Cat.SniffName' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tag' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreAnnotationAtStart()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS disable annotation at the start.
	 *
	 * @return void
	 */
	public function testMultiLineDocDisableAnnotationAtStart() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_DISABLE => '@phpcs:disable Stnd.Cat.SniffName -- Ensure PHPCS annotations are also retokenized correctly.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Something.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocDisableAnnotationAtStart()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS enable annotation at the start.
	 *
	 * @return void
	 */
	public function testMultiLineDocEnableAnnotationAtStart() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_ENABLE => '@phpcs:enable Stnd.Cat,Stnd.Other' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tag' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With description.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocEnableAnnotationAtStart()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignoreFile annotation in the middle.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreFileAnnotationInMiddle() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Check tokenization of PHPCS annotations within docblocks.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE_FILE => '@phpcs:ignoreFile' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Something.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreFileAnnotationInMiddle()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignore annotation in the middle.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreAnnotationInMiddle() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagBefore' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With Description' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE => '@phpcs:ignore Stnd.Cat.SniffName' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Something.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreAnnotationInMiddle()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS disable annotation in the middle.
	 *
	 * @return void
	 */
	public function testMultiLineDocDisableAnnotationInMiddle() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Check tokenization of PHPCS annotations within docblocks.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_DISABLE => '@phpcs:disable Stnd.Cat.SniffName -- Ensure PHPCS annotations are also retokenized correctly.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagAfter' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With Description' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocDisableAnnotationInMiddle()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS enable annotation in the middle.
	 *
	 * @return void
	 */
	public function testMultiLineDocEnableAnnotationInMiddle() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Check tokenization of PHPCS annotations within docblocks.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_ENABLE => '@phpcs:enable Stnd.Cat,Stnd.Other' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagAfter' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocEnableAnnotationInMiddle()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignoreFile annotation at the end.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreFileAnnotationAtEnd() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagBefore' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE_FILE => '@phpcs:ignoreFile' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreFileAnnotationAtEnd()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS ignore annotation at the end.
	 *
	 * @return void
	 */
	public function testMultiLineDocIgnoreAnnotationAtEnd() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Check tokenization of PHPCS annotations within docblocks.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_IGNORE => '@phpcs:ignore Stnd.Cat.SniffName' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocIgnoreAnnotationAtEnd()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS disable annotation at the end.
	 *
	 * @return void
	 */
	public function testMultiLineDocDisableAnnotationAtEnd() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagBefore' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With Description.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_DISABLE => '@phpcs:disable Stnd.Cat.SniffName -- Ensure PHPCS annotations are also retokenized correctly.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocDisableAnnotationAtEnd()


	/**
	 * Verify tokenization of a single line DocBlock containing a PHPCS enable annotation at the end.
	 *
	 * @return void
	 */
	public function testMultiLineDocEnableAnnotationAtEnd() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Check tokenization of PHPCS annotations within docblocks.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_PHPCS_ENABLE => '@phpcs:enable Stnd.Cat,Stnd.Other' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultiLineDocEnableAnnotationAtEnd()
}//end class
