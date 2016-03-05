<?php
/**
 *
 * @filesource   LinkTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\HTML5;

use chillerlan\BBCodeTest\Includes\Modules\HTML5TestBase;

/**
 * Class LinkTest
 */
class LinkTest extends HTML5TestBase{

	public function linkDataProvider(){
		return [
			['[url]https://github.com/chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">https://github.com/chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode title=\'some stuff\' class=some-css-class]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" title="some stuff" class="some-css-class blank">chillerlan/bbcode</a>'],
		];
	}

	/**
	 * @dataProvider linkDataProvider
	 */
	public function testLinkModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
