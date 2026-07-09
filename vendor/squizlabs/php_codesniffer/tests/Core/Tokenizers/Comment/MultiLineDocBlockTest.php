<?php
/**
 * Tests that multiline docblocks are tokenized correctly.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2024 PHPCSStandards and contributors
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\Comment;

/**
 * Tests that multiline docblocks are tokenized correctly.
 *
 * @covers PHP_CodeSniffer\Tokenizers\Comment
 */
final class MultiLineDocBlockTest extends CommentTestCase {



	/**
	 * Data provider.
	 *
	 * @see testDocblockOpenerCloser()
	 *
	 * @return array<string, array<string, string|int|array<int>>>
	 */
	public static function dataDocblockOpenerCloser() {
		return array(
			'Multi line docblock: no contents'            => array(
				'marker'       => '/* testEmptyDocblock */',
				'closerOffset' => 3,
				'expectedTags' => array(),
			),
			'Multi line docblock: variety of text and tags' => array(
				'marker'       => '/* testMultilineDocblock */',
				'closerOffset' => 95,
                // phpcs:ignore Squiz.Arrays.ArrayDeclaration.SingleLineNotAllowed
				'expectedTags' => array( 21, 29, 36, 46, 56, 63, 73, 80, 90 ),
			),
			'Multi line docblock: no leading stars'       => array(
				'marker'       => '/* testMultilineDocblockNoStars */',
				'closerOffset' => 32,
                // phpcs:ignore Squiz.Arrays.ArrayDeclaration.SingleLineNotAllowed
				'expectedTags' => array( 10, 16, 21, 27 ),
			),
			'Multi line docblock: indented'               => array(
				'marker'       => '/* testMultilineDocblockIndented */',
				'closerOffset' => 60,
                // phpcs:ignore Squiz.Arrays.ArrayDeclaration.SingleLineNotAllowed
				'expectedTags' => array( 21, 28, 38, 45, 55 ),
			),
			'Multi line docblock: opener not on own line' => array(
				'marker'       => '/* testMultilineDocblockOpenerNotOnOwnLine */',
				'closerOffset' => 10,
				'expectedTags' => array(),
			),
			'Multi line docblock: closer not on own line' => array(
				'marker'       => '/* testMultilineDocblockCloserNotOnOwnLine */',
				'closerOffset' => 11,
				'expectedTags' => array(),
			),
			'Multi line docblock: stars not aligned'      => array(
				'marker'       => '/* testMultilineDocblockStarsNotAligned */',
				'closerOffset' => 26,
				'expectedTags' => array(),
			),
		);
	}//end dataDocblockOpenerCloser()


	/**
	 * Verify tokenization of an empty, multi-line DocBlock.
	 *
     * @phpcs:disable Squiz.Arrays.ArrayDeclaration.SpaceBeforeDoubleArrow -- Readability is better with alignment.
	 *
	 * @return void
	 */
	public function testEmptyDocblock() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testEmptyDocblock()


	/**
	 * Verify tokenization of a multi-line DocBlock containing all possible tokens.
	 *
	 * @return void
	 */
	public function testMultilineDocblock() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'This is a multi-line docblock.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With blank lines, stars, tags, and tag descriptions.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tagWithoutDescription' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@since' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '10.3' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@deprecated' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '11.5' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@requires' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'PHP 7.1 -- PHPUnit tag.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tag-with-dashes-is-suppported' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Description.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@tag_with_underscores' ),
			array( T_DOC_COMMENT_WHITESPACE => '          ' ),
			array( T_DOC_COMMENT_STRING => 'Description.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'string    $p1 Description 1.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'int|false $p2 Description 2.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@return' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'void' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblock()


	/**
	 * Verify tokenization of a multi-line DocBlock with extra starts for the opener/closer and no stars on the lines between.
	 *
	 * @return void
	 */
	public function testMultilineDocblockNoStars() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/****' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_STRING => 'This is a multi-line docblock, but the lines are not marked with stars.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_STRING => 'Then again, the opener and closer have an abundance of stars.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_TAG => '@since' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '10.3' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'string    $p1 Description 1.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'int|false $p2 Description 2.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_TAG => '@return' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'void' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '**/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblockNoStars()


	/**
	 * Verify tokenization of a multi-line, indented DocBlock.
	 *
	 * @return void
	 */
	public function testMultilineDocblockIndented() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'This is a multi-line indented docblock.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'With blank lines, stars, tags, and tag descriptions.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@since' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '10.3' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@deprecated' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => '11.5' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'string    $p1 Description 1.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@param' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'int|false $p2 Description 2.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_TAG => '@return' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'void' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '     ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblockIndented()


	/**
	 * Verify tokenization of a multi-line DocBlock, where the opener is not on its own line.
	 *
	 * @return void
	 */
	public function testMultilineDocblockOpenerNotOnOwnLine() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Start of description' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'description continued.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblockOpenerNotOnOwnLine()


	/**
	 * Verify tokenization of a multi-line DocBlock, where the closer is not on its own line.
	 *
	 * @return void
	 */
	public function testMultilineDocblockCloserNotOnOwnLine() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Start of description' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'description continued. ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblockCloserNotOnOwnLine()


	/**
	 * Verify tokenization of a multi-line DocBlock with inconsistent indentation.
	 *
	 * @return void
	 */
	public function testMultilineDocblockStarsNotAligned() {
		$expectedSequence = array(
			array( T_DOC_COMMENT_OPEN_TAG => '/**' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Start of description.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => '   ' ),
			array( T_DOC_COMMENT_STRING => 'Line below this is missing a star.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '   ' ),
			array( T_DOC_COMMENT_STRING => 'Text' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Star indented.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_STAR => '*' ),
			array( T_DOC_COMMENT_WHITESPACE => ' ' ),
			array( T_DOC_COMMENT_STRING => 'Closer indented.' ),
			array( T_DOC_COMMENT_WHITESPACE => "\n" ),
			array( T_DOC_COMMENT_WHITESPACE => '    ' ),
			array( T_DOC_COMMENT_CLOSE_TAG => '*/' ),
		);

		$target = $this->getTargetToken( '/* ' . __FUNCTION__ . ' */', T_DOC_COMMENT_OPEN_TAG );

		$this->checkTokenSequence( $target, $expectedSequence );
	}//end testMultilineDocblockStarsNotAligned()
}//end class
