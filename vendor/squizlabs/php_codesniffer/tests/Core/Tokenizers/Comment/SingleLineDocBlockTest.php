<?php
/**
 * Tests that single line docblocks are tokenized correctly.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\Comment;

/**
 * Tests that single line docblocks are tokenized correctly.
 *
 * @covers PHP_CodeSniffer\Tokenizers\Comment
 */
final class SingleLineDocBlockTest extends CommentTestCase {



	/**
	 * Data provider.
	 *
	 * @see testDocblockOpenerCloser()
	 *
	 * @return array<string, array<string, string|int|array<int>>>
	 */
	public static function dataDocblockOpenerCloser() {
		return array(
			'Single line docblock: empty, no whitespace'  => array(
				'marker'       => '/* testEmptyDocblockNoWhiteSpace */',
				'closerOffset' => 1,
				'expectedTags' => array(),
			),
			'Single line docblock: only whitespace'       => array(
				'marker'       => '/* testEmptyDocblockWithWhiteSpace */',
				'closerOffset' => 2,
				'expectedTags' => array(),
			),
			'Single line docblock: just text'             => array(
				'marker'       => '/* testSingleLineDocblockNoTag */',
				'closerOffset' => 3,
				'expectedTags' => array(),
			),
			'Single line docblock: @var type before name' => array(
				'marker'       => '/* testSingleLineDocblockWithTag1 */',
				'closerOffset' => 5,
				'expectedTags' => array( 2 ),
			),
			'Single line docblock: @var name before type' => array(
				'marker'       => '/* testSingleLineDocblockWithTag2 */',
				'closerOffset' => 5,
				'expectedTags' => array( 2 ),
			),
			'Single line docblock: @see with description' => array(
				'marker'       => '/* testSingleLineDocblockWithTag3 */',
				'closerOffset' => 5,
				'expectedTags' => array( 2 ),
			),
		);
	}//end dataDocblockOpenerCloser()


	/**
	 * Verify an empty block comment is tokenized as T_COMMENT, not as a docblock.
	 *
	 * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testEmptyBlockCommentNoWhiteSpace() {
		$expectedSequence = array(
			array( T_COMMENT => '/**/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', array( T_COMMENT, T_DOC_COMMENT_OPEN_TAG ) );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testEmptyBlockCommentNoWhiteSpace()


	/**
	 * Verify tokenization of an empty, single line DocBlock without whitespace between the opener and closer.
	 *
     * @phpcs:disable Squiz.Arrays.ArrayDeclaration.SpaceBeforeDoubleArrow -- Readability is better with alignment.
	 *
	 * @return void
	 */
	public function testEmptyDocblockNoWhiteSpace() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testEmptyDocblockNoWhiteSpace()


	/**
	 * Verify tokenization of an empty, single line DocBlock.
	 *
	 * @return void
	 */
	public function testEmptyDocblockWithWhiteSpace() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testEmptyDocblockWithWhiteSpace()


	/**
	 * Verify tokenization of a single line DocBlock.
	 *
	 * @return void
	 */
	public function testSingleLineDocblockNoTag() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Just some text ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocblockNoTag()


	/**
	 * Verify tokenization of a single line DocBlock with a tag.
	 *
	 * @return void
	 */
	public function testSingleLineDocblockWithTag1() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@var' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '\SomeClass[] $var ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocblockWithTag1()


	/**
	 * Verify tokenization of a single line DocBlock with a tag.
	 *
	 * @return void
	 */
	public function testSingleLineDocblockWithTag2() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@var' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '$var \SomeClass[] ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocblockWithTag2()


	/**
	 * Verify tokenization of a single line DocBlock with a tag.
	 *
	 * @return void
	 */
	public function testSingleLineDocblockWithTag3() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@see' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Something::Else ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testSingleLineDocblockWithTag3()
}//end class
