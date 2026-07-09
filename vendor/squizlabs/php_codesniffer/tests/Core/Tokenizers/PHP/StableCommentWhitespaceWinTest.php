<?php
/**
 * Tests the comment tokenization with Windows line endings.
 *
 * Basically the same as the StableCommentWhitespaceTest, but now for
 * Windows line endings.
 *
 * @author    Juliette Reinders Folmer <phpcs_nospam@adviesenzo.nl>
 * @copyright 2020 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/HEAD/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizers\PHP;

use PHP_CodeSniffer\Tests\Core\Tokenizers\AbstractTokenizerTestCase;
use PHP_CodeSniffer\Util\Tokens;

final class StableCommentWhitespaceWinTest extends AbstractTokenizerTestCase {



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
			'slash comment, single line'           => array(
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
			'slash comment, single line, trailing' => array(
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
			'slash ignore annotation, single line' => array(
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
			'slash comment, multi-line'            => array(
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
			'slash comment, multi-line, indented'  => array(
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
			'hash comment, single line'            => array(
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
			'hash comment, single line, trailing'  => array(
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
			'hash comment, multi-line'             => array(
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
			'hash comment, multi-line, indented'   => array(
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
			'unclosed star comment at end of file' => array(
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
