<?php
/**
 *
 * @filesource   ImageTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\Markdown;

/**
 * Class ImageTest
 */
class ImageTest extends MarkdownTestBase{
	
	
	public function imageDataProvider(){
		return [
			['', '[img]javascript:alert(\'XSS\');[/img]'],
			['![image](https://travis-ci.org/chillerlan/bbcode.svg)', '[img]https://travis-ci.org/chillerlan/bbcode.svg[/img]'],
			['![foobar](https://travis-ci.org/chillerlan/bbcode.svg)', '[img alt=foobar]https://travis-ci.org/chillerlan/bbcode.svg[/img]'],
		];
	}


	/**
	 * @dataProvider imageDataProvider
	 */
	public function testImageModule($expected, $bbcode){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
