<?php
/**
 * Tests the comment tokenization.
 *
 * Comment have their own tokenization in PHPCS anyhow, including the PHPCS annotations.
 * However, as of PHP 8, the PHP native comment tokenization has changed.
 * Natively T_COMMENT tokens will no longer include a trailing newline.
 * PHPCS "forward-fills" the original tokenization to PHP 8.
 * This test file safeguards that.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class StableCommentWhitespaceTest extends AbstractTokenizerTestCase {



	/**
	 * Test that comment tokenization with new lines at the end of the comment is stable.
	 *
	 * @param string                       $testMarker     The comment prefacing the test.
	 * @param array<array<string, string>> $expectedTokens The tokenization expected.
	 *
	 * @dataProvider dataCommentTokenization
	 * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
	 *
	 * @return void
	 */
	public function testCommentTokenization( $testMarker, $expectedTokens ) {
		$tokens  = $this->phpcsFile->getTokens();
		$comment = $this->getTargetToken( $testMarker, Tokens::$commentTokens );

		foreach ( $expectedTokens as $tokenInfo ) {
			$this->assertSame(
				constant( $tokenInfo['type'] ),
				$tokens[ $comment ]['code'],
				'Token tokenized as ' . Tokens::tokenName( $tokens[ $comment ]['code'] ) . ', not ' . $tokenInfo['type'] . ' (code)'
			);
			$this->assertSame(
				$tokenInfo['type'],
				$tokens[ $comment ]['type'],
				'Token tokenized as ' . $tokens[ $comment ]['type'] . ', not ' . $tokenInfo['type'] . ' (type)'
			);
			$this->assertSame( $tokenInfo['content'], $tokens[ $comment ]['content'] );

			++$comment;
		}
	}//end testCommentTokenization()


	/**
	 * Data provider.
	 *
	 * @see testCommentTokenization()
	 *
	 * @return array<string, array<string, string|array<array<string, string>>>>
	 */
	public static function dataCommentTokenization() {
		return array(
			'slash comment, single line'              => array(
				'testMarker'     => '/* testSingleLineSlashComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, single line, trailing'    => array(
				'testMarker'     => '/* testSingleLineSlashCommentTrailing */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash ignore annotation, single line'    => array(
				'testMarker'     => '/* testSingleLineSlashAnnotation */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_PHPCS_DISABLE',
						'content' => '// phpcs:disable Stnd.Cat
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, multi-line'               => array(
				'testMarker'     => '/* testMultiLineSlashComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, multi-line, indented'     => array(
				'testMarker'     => '/* testMultiLineSlashCommentWithIndent */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment1
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment2
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, multi-line, ignore annotation as first line' => array(
				'testMarker'     => '/* testMultiLineSlashCommentWithAnnotationStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '// phpcs:ignore Stnd.Cat
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, multi-line, ignore annotation as middle line' => array(
				'testMarker'     => '/* testMultiLineSlashCommentWithAnnotationMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment1
',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '// @phpcs:ignore Stnd.Cat
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, multi-line, ignore annotation as last line' => array(
				'testMarker'     => '/* testMultiLineSlashCommentWithAnnotationEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Comment2
',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '// phpcs:ignore Stnd.Cat
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, single line'               => array(
				'testMarker'     => '/* testSingleLineStarComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Single line star comment */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, single line, trailing'     => array(
				'testMarker'     => '/* testSingleLineStarCommentTrailing */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star ignore annotation, single line'     => array(
				'testMarker'     => '/* testSingleLineStarAnnotation */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '/* phpcs:ignore Stnd.Cat */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, multi-line'                => array(
				'testMarker'     => '/* testMultiLineStarComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment3 */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, multi-line, indented'      => array(
				'testMarker'     => '/* testMultiLineStarCommentWithIndent */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '         * Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '         * Comment3 */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, multi-line, ignore annotation as first line' => array(
				'testMarker'     => '/* testMultiLineStarCommentWithAnnotationStart */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '/* @phpcs:ignore Stnd.Cat
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment3 */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, multi-line, ignore annotation as middle line' => array(
				'testMarker'     => '/* testMultiLineStarCommentWithAnnotationMiddle */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment1
',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => ' * phpcs:ignore Stnd.Cat
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment3 */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'star comment, multi-line, ignore annotation as last line' => array(
				'testMarker'     => '/* testMultiLineStarCommentWithAnnotationEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => ' * Comment2
',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => ' * phpcs:ignore Stnd.Cat */',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),

			'docblock comment, single line'           => array(
				'testMarker'     => '/* testSingleLineDocblockComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'docblock comment, single line, trailing' => array(
				'testMarker'     => '/* testSingleLineDocblockCommentTrailing */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'docblock ignore annotation, single line' => array(
				'testMarker'     => '/* testSingleLineDocblockAnnotation */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => 'phpcs:ignore Stnd.Cat.Sniff ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),

			'docblock comment, multi-line'            => array(
				'testMarker'     => '/* testMultiLineDocblockComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment1',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment2',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_TAG',
						'content' => '@tag',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'docblock comment, multi-line, indented'  => array(
				'testMarker'     => '/* testMultiLineDocblockCommentWithIndent */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '     ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment1',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '     ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment2',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '     ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '     ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_TAG',
						'content' => '@tag',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '     ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'docblock comment, multi-line, ignore annotation' => array(
				'testMarker'     => '/* testMultiLineDocblockCommentWithAnnotation */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => 'phpcs:ignore Stnd.Cat',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_TAG',
						'content' => '@tag',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'docblock comment, multi-line, ignore annotation as tag' => array(
				'testMarker'     => '/* testMultiLineDocblockCommentWithTagAnnotation */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_DOC_COMMENT_OPEN_TAG',
						'content' => '/**',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_PHPCS_IGNORE',
						'content' => '@phpcs:ignore Stnd.Cat',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STAR',
						'content' => '*',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_TAG',
						'content' => '@tag',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_STRING',
						'content' => 'Comment',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => '
',
					),
					array(
						'type'    => 'T_DOC_COMMENT_WHITESPACE',
						'content' => ' ',
					),
					array(
						'type'    => 'T_DOC_COMMENT_CLOSE_TAG',
						'content' => '*/',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'hash comment, single line'               => array(
				'testMarker'     => '/* testSingleLineHashComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'hash comment, single line, trailing'     => array(
				'testMarker'     => '/* testSingleLineHashCommentTrailing */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'hash comment, multi-line'                => array(
				'testMarker'     => '/* testMultiLineHashComment */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment1
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment2
',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'hash comment, multi-line, indented'      => array(
				'testMarker'     => '/* testMultiLineHashCommentWithIndent */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment1
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment2
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '    ',
					),
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Comment3
',
					),
					array(
						'type'    => 'T_WHITESPACE',
						'content' => '
',
					),
				),
			),
			'slash comment, single line, without new line at end' => array(
				'testMarker'     => '/* testSingleLineSlashCommentNoNewLineAtEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '// Slash ',
					),
					array(
						'type'    => 'T_CLOSE_TAG',
						'content' => '?>
',
					),
				),
			),
			'hash comment, single line, without new line at end' => array(
				'testMarker'     => '/* testSingleLineHashCommentNoNewLineAtEnd */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '# Hash ',
					),
					array(
						'type'    => 'T_CLOSE_TAG',
						'content' => '?>
',
					),
				),
			),
			'unclosed star comment at end of file'    => array(
				'testMarker'     => '/* testCommentAtEndOfFile */',
				'expectedTokens' => array(
					array(
						'type'    => 'T_COMMENT',
						'content' => '/* Comment',
					),
				),
			),
		);
	}//end dataCommentTokenization()
}//end class
