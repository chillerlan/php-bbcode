<?php
/**
 *
 * @filesource   LinkTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\Markdown;

/**
 * Class LinkTest
 */
class LinkTest extends MarkdownTestBase{
	
	public function linkDataProvider(){
		return [
			['', '[url]javascript:alert(\'XSS\');[/url]'],
			['', '[url=javascript:alert(\'XSS\');]test[/url]'],
			['', '[url=javascript:alert(\'XSS\');]javascript:alert(\'XSS\');[/url]'],
			['https://travis-ci.org/chillerlan/bbcode', '[url]https://travis-ci.org/chillerlan/bbcode[/url]'],
			['https://travis-ci.org/chillerlan/bbcode', '[url=https://travis-ci.org/chillerlan/bbcode][/url]'],
			['[Travis CI: chillerlan/bbcode](https://travis-ci.org/chillerlan/bbcode)', '[url=https://travis-ci.org/chillerlan/bbcode]Travis CI: chillerlan/bbcode[/url]'],
			['http://youtu.be/6r1-HTiwGiY', '[video]http://youtu.be/6r1-HTiwGiY[/video]'],
		];
	}

	/**
	 * @dataProvider linkDataProvider
	 */
	public function testLinkModule($expected, $bbcode){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}
	
}
