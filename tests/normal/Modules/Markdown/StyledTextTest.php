<?php
/**
 *
 * @filesource   StyledTextTest.php
 * @created      04.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\Markdown;

use chillerlan\bbcodeTest\Includes\Modules\MarkdownTestBase;

/**
 * Class StyledTextTest
 */
class StyledTextTest extends MarkdownTestBase{

	public function styledTextDataProvider(){
		return [
			['[b]bold[/b]', '**bold**'],
			['[strong]bold[/strong]', '**bold**'],
			['[del]strikethrough[/del]', '~~strikethrough~~'],
			['[s]strikethrough[/s]', '~~strikethrough~~'],
			['[c]inline-code[/c]', '`inline-code`'],
			['[i]italic[/i]', '_italic_'],
		];
	}

	/**
	 * @dataProvider styledTextDataProvider
	 */
	public function testStyledTextModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
